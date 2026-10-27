<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleItemDiscount;
use App\Models\SalePayment;
use App\Models\CashShift;
use App\Models\Product;
use App\Models\ProductQuantityRule;
use App\Models\InventoryBalance;
use App\Models\InventoryMovement;
use App\Models\CashMovement;
use App\Models\CreditEntry;
use App\Models\DocumentSequence;
use App\Models\PaymentMethod;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Throwable;

class CompleteSaleService
{
    public function execute(array $data, int $userId): Sale
    {
        $transactionStartedNs = hrtime(true);
        $isLocalEnvironment = app()->environment('local');

        try {
            $sale = DB::transaction(function () use ($data, $userId, $isLocalEnvironment) {

                // 1. Validate Open Shift
                $shift = CashShift::where('terminal_id', $data['terminal_id'])
                    ->where('status', 'open')
                    ->lockForUpdate()
                    ->first();

                if (!$shift) {
                    throw new Exception(
                        'No open shift found for this terminal. You must open a shift before selling.'
                    );
                }

                // 2. Generate Sequential Document Number safely
                $sequence = DocumentSequence::where('store_id', $data['store_id'])
                    ->where('document_type', 'sale')
                    ->lockForUpdate()
                    ->first();

                $documentNumber = $sequence->formatNextNumber();
                $sequence->increment('next_number');

                $customer = isset($data['customer_id'])
                    ? Customer::find($data['customer_id'])
                    : null;

                $priceListId = $customer?->price_list_id ?? null;

                // 3. Initialize Sale Header
                $sale = Sale::create([
                    'uuid' => Str::uuid(),
                    'store_id' => $data['store_id'],
                    'terminal_id' => $data['terminal_id'],
                    'shift_id' => $shift->id,
                    'cashier_user_id' => $userId,
                    'customer_id' => $customer?->id,
                    'number' => $documentNumber,
                    'status' => 'completed',
                    'customer_name_snapshot' => $customer?->display_name,
                    'sold_at' => now(),
                    'completed_at' => now(),
                ]);

                $subtotal = 0;
                $taxTotal = 0;
                $discountTotal = 0;
                $total = 0;

                // 4. Process Items and Lock Inventory (Skipped if APP_ENV=local)
                foreach ($data['items'] as $itemData) {
                    $product = Product::with(['inventoryUnit', 'saleUnit'])
                        ->findOrFail($itemData['product_id']);

                    $balance = null;

                    if (!$isLocalEnvironment) {
                        $balance = InventoryBalance::where('store_id', $data['store_id'])
                            ->where('product_id', $product->id)
                            ->lockForUpdate()
                            ->first();

                        if (
                            !$product->allow_negative_inventory
                            && (!$balance || $balance->quantity_available < $itemData['quantity'])
                        ) {
                            throw new Exception(
                                "Insufficient inventory for product: {$product->name}"
                            );
                        }
                    } else {
                        // In local environment, grab balance optionally without locking/failing if it doesn't exist
                        $balance = InventoryBalance::where('store_id', $data['store_id'])
                            ->where('product_id', $product->id)
                            ->first();
                    }

                    $unitPrice = null;

                    // A. Check for custom customer-specific product price override first
                    if ($customer) {
                        $customPriceRecord = DB::table('customer_product_prices')
                            ->where('customer_id', $customer->id)
                            ->where('product_id', $product->id)
                            ->value('price');

                        if (!is_null($customPriceRecord)) {
                            $unitPrice = $customPriceRecord;
                        }
                    }

                    // B. Fallback to Customer's Price List
                    if (is_null($unitPrice) && $priceListId) {
                        $unitPrice = DB::table('product_prices')
                            ->where('product_id', $product->id)
                            ->where('price_list_id', $priceListId)
                            ->value('sale_price');
                    }

                    // C. Fallback to Store Default Price List
                    if (is_null($unitPrice)) {
                        $unitPrice = DB::table('product_prices')
                            ->join(
                                'price_lists',
                                'product_prices.price_list_id',
                                '=',
                                'price_lists.id'
                            )
                            ->where('product_prices.product_id', $product->id)
                            ->where('price_lists.store_id', $data['store_id'])
                            ->value('product_prices.sale_price') ?? 0.00;
                    }

                    $lineGross = $unitPrice * $itemData['quantity'];

                    // Evaluate pricing rules
                    $itemDiscount = 0;
                    $appliedRuleName = null;

                    $bestRule = ProductQuantityRule::where('product_id', $product->id)
                        ->where('is_active', true)
                        ->where('minimum_quantity', '<=', $itemData['quantity'])
                        ->orderBy('priority', 'desc')
                        ->orderBy('minimum_quantity', 'desc')
                        ->first();

                    if ($bestRule) {
                        $appliedRuleName = $bestRule->name;
                        $timesToApply = 1;

                        $groupQty = $bestRule->group_quantity > 0
                            ? $bestRule->group_quantity
                            : $bestRule->minimum_quantity;

                        if ($bestRule->repeatable) {
                            $timesToApply = floor(
                                $itemData['quantity'] / $groupQty
                            );
                        }

                        if ($timesToApply > 0) {
                            $affectedQty = $timesToApply * $groupQty;

                            if ($bestRule->rule_type === 'percentage') {
                                $itemDiscount = ($unitPrice * $affectedQty)
                                    * ($bestRule->discount_value / 100);
                            } elseif ($bestRule->rule_type === 'unit_discount') {
                                $itemDiscount = $bestRule->discount_value
                                    * $affectedQty;
                            } elseif ($bestRule->rule_type === 'fixed_price') {
                                $standardPriceForAffected = $affectedQty * $unitPrice;
                                $discountedPriceForAffected = $timesToApply
                                    * $bestRule->discount_value;

                                $itemDiscount = max(
                                    0,
                                    $standardPriceForAffected - $discountedPriceForAffected
                                );
                            }
                        }
                    }

                    $lineTotal = $lineGross - $itemDiscount;

                    // 5. Create Sale Item
                    $saleItem = SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'unit_id' => $product->sale_unit_id,
                        'sku_snapshot' => $product->sku,
                        'name_snapshot' => $product->name,
                        'unit_snapshot' => $product->saleUnit->name,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $unitPrice,
                        'gross_total' => $lineGross,
                        'line_total' => $lineTotal,
                    ]);

                    // 5.1 Save Item Discount if applicable
                    if ($itemDiscount > 0 && $appliedRuleName) {
                        SaleItemDiscount::create([
                            'sale_item_id' => $saleItem->id,
                            'name' => $appliedRuleName,
                            'discount_type' => 'quantity_rule',
                            'amount' => $itemDiscount,
                        ]);

                        $discountTotal += $itemDiscount;
                    }

                    // 6. Update Inventory Balances safely (Skipped if APP_ENV=local)
                    if ($balance && !$isLocalEnvironment) {
                        $quantitySold = (float) $itemData['quantity'];

                        $newQuantityOnHand =
                            (float) $balance->quantity_on_hand - $quantitySold;

                        DB::table('inventory_balances')
                            ->where('store_id', $data['store_id'])
                            ->where('product_id', $product->id)
                            ->update([
                                'quantity_on_hand' => $newQuantityOnHand,
                                'version' => DB::raw('version + 1'),
                                'updated_at' => now(),
                            ]);

                        $balance->quantity_on_hand = $newQuantityOnHand;
                        $balance->version = (int) $balance->version + 1;
                    }

                    // 7. Write Immutable Inventory Movement Ledger (Optional: skipped or recorded for local testing)
                    if (!$isLocalEnvironment) {
                        InventoryMovement::create([
                            'uuid' => Str::uuid(),
                            'store_id' => $data['store_id'],
                            'product_id' => $product->id,
                            'user_id' => $userId,
                            'shift_id' => $shift->id,
                            'movement_type' => 'sale',
                            'quantity' => -$itemData['quantity'],
                            'balance_after' => $balance?->quantity_on_hand,
                            'reference_type' => Sale::class,
                            'reference_id' => $sale->id,
                            'occurred_at' => now(),
                        ]);
                    }

                    $subtotal += $lineGross;
                    $total += $lineTotal;
                }

                // 8. Process Payments
                $paidTotal = 0;

                foreach ($data['payments'] as $paymentData) {
                    $method = PaymentMethod::findOrFail(
                        $paymentData['payment_method_id']
                    );

                    $change = 0;

                    if ($paymentData['received_amount'] > $paymentData['amount']) {
                        $change = $paymentData['received_amount']
                            - $paymentData['amount'];
                    }

                    SalePayment::create([
                        'sale_id' => $sale->id,
                        'payment_method_id' => $method->id,
                        'shift_id' => $shift->id,
                        'user_id' => $userId,
                        'amount' => $paymentData['amount'],
                        'received_amount' => $paymentData['received_amount']
                            ?? $paymentData['amount'],
                        'change_amount' => $change,
                        'reference' => $paymentData['reference'] ?? null,
                        'paid_at' => now(),
                    ]);

                    // 9. Write Cash Movement
                    if ($method->affects_cash_drawer) {
                        CashMovement::create([
                            'cash_shift_id' => $shift->id,
                            'user_id' => $userId,
                            'movement_type' => 'cash_sale',
                            'amount' => $paymentData['amount'],
                            'reference_type' => Sale::class,
                            'reference_id' => $sale->id,
                            'occurred_at' => now(),
                        ]);
                    }

                    // 10. Write Customer Credit
                    if ($method->code === 'customer_credit') {
                        if (!$customer || !$customer->creditAccount) {
                            throw new Exception(
                                'Customer is required and must have an active credit account.'
                            );
                        }

                        CreditEntry::create([
                            'credit_account_id' => $customer->creditAccount->id,
                            'sale_id' => $sale->id,
                            'user_id' => $userId,
                            'entry_type' => 'charge',
                            'amount' => $paymentData['amount'],
                            'occurred_at' => now(),
                        ]);
                    }

                    $paidTotal += $paymentData['amount'];
                }

                // 11. Finalize Sale Totals
                $sale->update([
                    'subtotal' => $subtotal,
                    'tax_total' => $taxTotal,
                    'discount_total' => $discountTotal,
                    'total' => $total,
                    'paid_total' => $paidTotal,
                ]);

                return $sale->load([
                    'items.discounts',
                    'payments',
                    'customer',
                ]);
            });

            Log::info('pos.sale_transaction.completed', [
                'sale_id' => $sale->id,
                'store_id' => $data['store_id'],
                'item_count' => count($data['items']),
                'payment_count' => count($data['payments']),
                'transaction_total_ms' => round(
                    (hrtime(true) - $transactionStartedNs) / 1_000_000,
                    2
                ),
            ]);

            return $sale;
        } catch (Throwable $e) {
            Log::error('pos.sale_transaction.failed', [
                'store_id' => $data['store_id'] ?? null,
                'terminal_id' => $data['terminal_id'] ?? null,
                'user_id' => $userId,
                'item_count' => isset($data['items']) ? count($data['items']) : 0,
                'payment_count' => isset($data['payments']) ? count($data['payments']) : 0,
                'transaction_elapsed_ms' => round(
                    (hrtime(true) - $transactionStartedNs) / 1_000_000,
                    2
                ),
                'exception' => get_class($e),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}