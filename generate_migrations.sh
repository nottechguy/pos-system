#!/bin/bash

echo "Starting migration and model generation..."

# 1-2. Business
php artisan make:model BusinessCategory -m; sleep 1
php artisan make:model Business -m; sleep 1

# 3-7. Users & Auth
php artisan make:model Role -m; sleep 1
php artisan make:model Permission -m; sleep 1
php artisan make:migration create_users_table; sleep 1
php artisan make:migration create_permission_role_table; sleep 1
php artisan make:migration create_permission_user_table; sleep 1

# 8-11. Stores & Terminals
php artisan make:model Store -m; sleep 1
php artisan make:migration create_store_user_table; sleep 1
php artisan make:model PosTerminal -m; sleep 1
php artisan make:model DocumentSequence -m; sleep 1

# 12-13. Departments
php artisan make:model Department -m; sleep 1
php artisan make:model ProductCategory -m; sleep 1

# 14-16. Customers
php artisan make:model Customer -m; sleep 1
php artisan make:model CustomerAddress -m; sleep 1
php artisan make:model CustomerDevice -m; sleep 1

# 17-19. Suppliers
php artisan make:model Supplier -m; sleep 1
php artisan make:model SupplierAddress -m; sleep 1
php artisan make:model SupplierContact -m; sleep 1

# 20-25. Products Catalog
php artisan make:model MeasurementUnit -m; sleep 1
php artisan make:model Product -m; sleep 1
php artisan make:model ProductBarcode -m; sleep 1
php artisan make:model ProductImage -m; sleep 1
php artisan make:migration create_product_components_table; sleep 1
php artisan make:migration create_supplier_product_table; sleep 1

# 26-27. Taxes
php artisan make:model Tax -m; sleep 1
php artisan make:migration create_product_tax_table; sleep 1

# 28-30. Pricing
php artisan make:model PriceList -m; sleep 1
php artisan make:model ProductPrice -m; sleep 1
php artisan make:model ProductQuantityRule -m; sleep 1

# 31-32. Inventory Balances
php artisan make:model StoreProduct -m; sleep 1
php artisan make:model InventoryBalance -m; sleep 1

# 33. Shifts (Dependencies)
php artisan make:model CashShift -m; sleep 1

# 34. Inventory Ledger
php artisan make:model InventoryMovement -m; sleep 1

# 35-36. Transfers
php artisan make:model StockTransfer -m; sleep 1
php artisan make:model StockTransferItem -m; sleep 1

# 37-38. Stock Counts
php artisan make:model StockCount -m; sleep 1
php artisan make:model StockCountItem -m; sleep 1

# 39-42. Purchasing
php artisan make:model PurchaseOrder -m; sleep 1
php artisan make:model PurchaseOrderItem -m; sleep 1
php artisan make:model GoodsReceipt -m; sleep 1
php artisan make:model GoodsReceiptItem -m; sleep 1

# 43-45. Quotations
php artisan make:model Quotation -m; sleep 1
php artisan make:model QuotationItem -m; sleep 1
php artisan make:model OutboundEmail -m; sleep 1

# 46. Payments
php artisan make:model PaymentMethod -m; sleep 1

# 47-51. Sales
php artisan make:model Sale -m; sleep 1
php artisan make:model SaleItem -m; sleep 1
php artisan make:model SaleItemDiscount -m; sleep 1
php artisan make:model SaleItemTax -m; sleep 1
php artisan make:model SalePayment -m; sleep 1

# 52-54. Returns
php artisan make:model SaleReturn -m; sleep 1
php artisan make:model SaleReturnItem -m; sleep 1
php artisan make:model SaleReturnPayment -m; sleep 1

# 55-57. Cash Management
php artisan make:model CashMovement -m; sleep 1
php artisan make:model CashDenomination -m; sleep 1
php artisan make:model CashCountLine -m; sleep 1

# 58-59. POS Interface persistence
php artisan make:model SuspendedSale -m; sleep 1
php artisan make:model SuspendedSaleItem -m; sleep 1

# 60-61. Customer Credit
php artisan make:model CreditAccount -m; sleep 1
php artisan make:model CreditEntry -m; sleep 1

# 62-64. Notifications
php artisan make:model NotificationTemplate -m; sleep 1
php artisan make:model NotificationCampaign -m; sleep 1
php artisan make:model NotificationDelivery -m; sleep 1

# 65. Settings
php artisan make:model Setting -m; sleep 1

# 66. Audit
php artisan make:model AuditLog -m; sleep 1

# 67. Summaries
php artisan make:model DailyStoreSummary -m;

echo "Done! All 67 tables scaffolded."