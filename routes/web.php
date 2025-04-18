<?php

use App\Http\Controllers\InvoiceGenerator;
use App\Http\Controllers\OrdersController;
use App\Livewire\Admin\Categories;
use App\Livewire\Forms\CustomerForm;
use App\Livewire\Admin\Customers as AdminCustomers;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Invoices;
use App\Livewire\Admin\Monitor;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\Pos;
use App\Livewire\Forms\ProductForm;
use App\Livewire\Admin\Products;
use App\Livewire\Forms\SupplierForm;
use App\Livewire\Admin\Suppliers;
use App\Livewire\Admin\Transactions;
use App\Livewire\Forms\UserForm;
use App\Livewire\Admin\Users;
use App\Livewire\Forms\CategoryInfo;
use App\Livewire\Forms\InvoiceForm;
use App\Livewire\Forms\OrderForm;
use App\Livewire\Forms\ProfileForm;
use App\Livewire\Forms\SupplierInfo;
use App\Livewire\OrderDetails;
use App\Livewire\Settings;
use App\Livewire\SystemInfo;
use App\Livewire\ViewInvoice;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Illuminate\Support\Facades\Storage;
use Google\Service\Drive;

Route::middleware('guest')->group(function () {
    Route::get('login', App\Livewire\Auth\LoginForm::class)->name('login');
    Route::get('forgot-password', App\Livewire\Auth\ForgetPasswordForm::class)->name('forget-password');
    Route::get('reset-password/{token}', App\Livewire\Auth\ResetForm::class)->name('reset-password');

    Route::get('/verify-otp', App\Livewire\Auth\VerifyOTP::class)->name('otp.verify');
});





Route::get('invoice', [InvoiceGenerator::class, 'index'])->name('invoice.generate');
## Prefix Dashboard #

// Admin  and manager routes
Route::middleware(['auth', 'isOnline', 'checkRole:admin,manager'])->group(function () {
    Route::get('/users', Users::class)->name('users');
    Route::get('/users/create', UserForm::class)->name('users.create');
    Route::get('/users/edit/{user}', UserForm::class)->name('users.edit');

    Route::get('/settings/config', Settings::class)->name('settings');
    Route::get('/settings/system-info', SystemInfo::class)->name('settings.sys-info');
    Route::get('/settings/preferences', \App\Livewire\PreferencesSettings::class)->name('settings.preferences');


    Route::get('/google-auth', function () {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->addScope('https://www.googleapis.com/auth/drive.file');

        if (!request()->has('code')) {
            // Step 1: Redirect to Google
            $authUrl = $client->createAuthUrl();
            return redirect()->away($authUrl);
        }

        // Step 2: Callback from Google
        $accessToken = $client->fetchAccessTokenWithAuthCode(request('code'));

        if (isset($accessToken['error'])) {
            return redirect()->route('dashboard')->with('errorMsg', 'Google Drive authorization failed: ' . $accessToken['error_description']);
        }

        Storage::put('google/token.json', json_encode($accessToken));
        return redirect()->route('dashboard')->with('successMsg', 'Google Drive authorized successfully!');
    });

    Route::get('download-file/{fileId}', function ($fileId) {
        $filePath = downloadFileFromGoogleDrive($fileId);

        if ($filePath) {
            return response()->download($filePath);
        }

        return 'Failed to download file.';
    })->name('downloadFile');
});

// function downloadFileFromGoogleDrive($fileId)
// {
//     $client = new Client();
//     $client->setAuthConfig(public_path('storage/google/credentials.json'));
//     $client->addScope(Drive::DRIVE_READONLY);

//     $tokenPath = public_path('storage/google/token.json');
//     $accessToken = json_decode(file_get_contents($tokenPath), true);
//     $client->setAccessToken($accessToken);

//     // Check if the token is expired
//     if ($client->isAccessTokenExpired()) {
//         return 'Token expired. Reauthorize.';
//     }

//     $service = new Drive($client);

//     // Download the file
//     $response = $service->files->get($fileId, [
//         'alt' => 'media',
//     ]);

//     $content = $response->getBody()->getContents();

//     // Save the file to the local server
//     $filePath = public_path('storage/downloads/' . $fileId . '.zip');
//     file_put_contents($filePath, $content);

//     return $filePath; // Return the path where the file is saved locally
// }
// General route
Route::middleware(['auth', 'isOnline', 'checkRole:admin,cashier,manager'])->group(function () {

    Route::get('/users/edit/{user}', UserForm::class)->name('users.edit');

    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/categories/category/{category}/products', CategoryInfo::class)->name('category.products.info');

    Route::get('/products', Products::class)->name('products');
    Route::get('products/create', ProductForm::class)->name('products.create');
    Route::get('/products/edit/{product}', ProductForm::class)->name('products.edit');

    Route::get('/orders', Orders::class)->name('orders');

    Route::get('/users/profile/{user}', ProfileForm::class)->name('users.profile');


    Route::get('/customers', AdminCustomers::class)->name('customers');
    Route::get('/customers/create', CustomerForm::class)->name('customers.create');
    Route::get('/customers/edit/{customer}', CustomerForm::class)->name('customers.edit');



    Route::get('/transactions', Transactions::class)->name('transactions');


    Route::get('/orders', Orders::class)->name('orders');
    Route::get('/orders/edit/{order}', OrderDetails::class)->name('orders.edit');
    Route::get('/orders/details/{order}', OrderDetails::class)->name('orders.details');

    Route::get('/orders/create', OrderForm::class)->name('orders.create');


    Route::get('/invoices', Invoices::class)->name('invoices');
    Route::get('/invoices/edit/{invoice}', InvoiceForm::class)->name('invoices.edit');
    Route::get('/invoices/create', InvoiceForm::class)->name('invoices.create');
    Route::get('/invoices/details/{invoice}', ViewInvoice::class)->name('invoices.details');

    Route::get('/invoices/pdf-download/{invoice}', [InvoiceGenerator::class, 'downloadPdf'])->name('invoices.download');


    Route::get('/orders/order-pdf-download/{order}', [InvoiceGenerator::class, 'downloadOrderPdf'])->name('order.pdf.download');


    Route::get('/suppliers', Suppliers::class)->name('suppliers');
    Route::get('/suppliers/create', SupplierForm::class)->name('suppliers.create');
    Route::get('/suppliers/edit/{supplier}', SupplierForm::class)->name('suppliers.edit');

    Route::get('/suppliers/supplier/{supplier}/products', SupplierInfo::class)->name('supplier.products.info');

    Route::get('/receipt/{orderId}', [OrdersController::class, 'generateReceipt'])->name('generateReceipt');



    Route::get('/products/stock-level/{type}', App\Livewire\StockAlerts::class)->name('products.stock-levels');
    // Route::get('/products/out-of-stock', StockAlerts::class)->name('products.outofstock');
});

// COMMON
Route::middleware(['auth', 'isOnline', 'isCommon'])->group(function () {
    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/pos', Pos::class)->name('pos');
    Route::get('/pos/monitor', Monitor::class)->name('pos.monitor');
});
