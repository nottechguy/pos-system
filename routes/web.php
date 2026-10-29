<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CatalogSyncController;

use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\BusinessSetupController;
use App\Http\Controllers\Admin\CashierController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LabelPrinterController;
use App\Http\Controllers\Admin\PosTerminalController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductBarcodeController;
use App\Http\Controllers\Admin\ProductBoxController;
use App\Http\Controllers\Admin\ProductPackageController;
use App\Http\Controllers\Admin\ProductPricingController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\PaymentMethod;


// Root Route Redirection
Route::get('/', function () {
    return Auth::check() 
        ? redirect()->route('admin.dashboard') 
        : redirect()->route('login');
});

Route::get('/admin/pos/payment-methods', function () {
    $paymentMethods = PaymentMethod::query()
        ->where('is_active', true)
        ->get()
        ->map(function (PaymentMethod $paymentMethod) {
            $translationKey = 'payment_methods.' . $paymentMethod->code . '.name';

            return [
                'id' => $paymentMethod->id,
                'code' => $paymentMethod->code,
                'name' => __($translationKey),
                'requires_reference' => $paymentMethod->requires_reference,
                'affects_cash_drawer' => $paymentMethod->affects_cash_drawer,
            ];
        });

    return response()->json($paymentMethods);
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');


    // Business Info Setup
    Route::get('setup/business', [BusinessSetupController::class, 'create'])->name('setup.business');
    Route::post('setup/business', [BusinessSetupController::class, 'store'])->name('setup.business.store');

    // Business Management (Standard synchronous forms)
    Route::get('business', [BusinessController::class, 'edit'])->name('business.edit');
    Route::put('business/{business}', [BusinessController::class, 'update'])->name('business.update');

    Route::resource('stores', StoreController::class);
    Route::prefix('stores/{store}')->name('stores.')->group(function () {
        Route::resource('terminals', PosTerminalController::class)->except(['show']);
    });

    Route::resource('departments', DepartmentController::class);

    Route::post('products/scan-search', [ProductController::class, 'scanSearch'])->name('products.scan_search');
    Route::post('products/search-components', [ProductController::class, 'searchComponentsAjax'])->name('products.search_components');
    Route::resource('products', ProductController::class);

    // Product Pricing & Modular Configuration Management
    Route::prefix('products/{product}')->name('products.')->group(function () {

        Route::post('images', [ProductImageController::class, 'store'])->name('images.store');
        Route::delete('images/{image}', [ProductImageController::class, 'destroy'])->name('images.destroy');
    
        // Barcodes Management
        Route::get('barcodes', [ProductBarcodeController::class, 'index'])->name('barcodes.index');
        Route::post('barcodes', [ProductBarcodeController::class, 'store'])->name('barcodes.store');
        Route::put('barcodes/{barcode}', [ProductBarcodeController::class, 'update'])->name('barcodes.update');
        Route::delete('barcodes/{barcode}', [ProductBarcodeController::class, 'destroy'])->name('barcodes.destroy');
    
        // Box Configuration Management
        Route::get('box', [ProductBoxController::class, 'index'])->name('box.index');
        Route::post('box', [ProductBoxController::class, 'store'])->name('box.store');
        Route::post('box-components', [ProductBoxController::class, 'storeComponent'])->name('box.components.store');
        Route::delete('box-components/{component}', [ProductBoxController::class, 'destroyComponent'])->name('box.components.destroy');

        // Package Configuration Management (Cleanly nested inside products/{product})
        Route::get('package', [ProductPackageController::class, 'index'])->name('package.index');
        Route::post('package', [ProductPackageController::class, 'store'])->name('package.store');
        Route::post('package/add', [ProductPackageController::class, 'storeComponent'])->name('package.storeComponent');
        Route::delete('package/{component}', [ProductPackageController::class, 'destroyComponent'])->name('package.destroyComponent');

        Route::get('pricing', [ProductPricingController::class, 'index'])->name('pricing.index');
        Route::post('pricing', [ProductPricingController::class, 'storePrices'])->name('pricing.store');
        
        // Quantity/Bulk Rules
        Route::post('rules', [ProductPricingController::class, 'storeRule'])->name('rules.store');
        Route::delete('rules/{rule}', [ProductPricingController::class, 'destroyRule'])->name('rules.destroy');
    });

    // Initial Stock Setup Route after Pricing
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
        
        Route::get('products/{product}/stock/create', [InventoryController::class, 'createStock'])->name('stock.create');
        Route::post('products/{product}/stock', [InventoryController::class, 'storeStock'])->name('stock.store');
    });

    // Scale Integration Routes
    Route::prefix('scale')->name('scale.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ScaleController::class, 'index'])->name('index');
        Route::post('/search', [App\Http\Controllers\Admin\ScaleController::class, 'search'])->name('search');
    });

    // Main Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('shifts', ShiftController::class);

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/search', [PosController::class, 'search'])->name('search');
        Route::post('/promotions/search', [PosController::class, 'searchPromotions'])->name('promotions.search');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
    });

    Route::prefix('pos/cashier')->name('pos.cashier.')->group(function () {
        Route::get('/list', [CashierController::class, 'listCashiers'])->name('list');
        Route::post('/switch', [CashierController::class, 'switchCashier'])->name('switch');
    });

    Route::resource('suppliers', SupplierController::class);

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/movements', [InventoryController::class, 'movements'])->name('movements');
    });

    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);

    // Label Printer Module Routes
    Route::prefix('label-printer')->name('label_printer.')->group(function () {
        Route::get('/', [LabelPrinterController::class, 'index'])->name('index');
        Route::post('/search', [LabelPrinterController::class, 'search'])->name('search');
        Route::post('/print', [LabelPrinterController::class, 'print'])->name('print');
    });

    // Sales History & Reprint Routes
    Route::resource('sales', SaleController::class)->only(['index', 'show']);
    Route::post('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    // Customer Management & Custom Prices
    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/prices', [CustomerController::class, 'storeCustomPrice'])->name('customers.prices.store');
    Route::delete('customers/{customer}/prices/{customPrice}', [CustomerController::class, 'destroyCustomPrice'])->name('customers.prices.destroy');
    
    Route::post('/customers/search', [PosController::class, 'searchCustomer'])->name('customers.search');
    Route::post('/recalculate-cart', [PosController::class, 'recalculateCart'])->name('recalculate_cart');

    /*
    // Modules (Placeholders for CRUD controllers)
    // POS Route (to be built out later as an SPA)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');

    Route::resource('quotations', QuotationController::class);
    Route::resource('customers', CustomerController::class);*/
    Route::resource('users', UserController::class); // Employees
   /* Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('shifts', ShiftController::class);
    Route::resource('credits', CreditController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('settings', SettingController::class); */
});