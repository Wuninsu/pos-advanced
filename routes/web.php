<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\InvoiceGenerator;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UserController;
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
use App\Livewire\ForgetPasswordForm;
use App\Livewire\Forms\CategoryInfo;
use App\Livewire\Forms\InvoiceForm;
use App\Livewire\Forms\OrderForm;
use App\Livewire\Forms\ProfileForm;
use App\Livewire\Forms\SupplierInfo;
use App\Livewire\LoginForm;
use App\Livewire\OrderDetails;
use App\Livewire\ResetForm;
use App\Livewire\Settings;
use App\Livewire\ViewInvoice;
use App\Models\Customers;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('/', Dashboard::class)->name('admin.dashboard');
    // Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    //     Route::get('login', 'login')->name('login');
    //     Route::post('auth/login', 'authLogin')->name('auth.login');
    //     Route::get('register', 'register')->name('register');
    //     Route::post('account/register', 'accountSignup')->name('account.signup');
    //     Route::get('forgot-password', 'forgotPassword')->name('forgot.password');
    //     Route::post('reset-password', 'reset')->name('reset');
    // });

    Route::get('login', LoginForm::class)->name('login');
    Route::get('forgot-password', ForgetPasswordForm::class)->name('forget-password');
    Route::get('reset-password/{token}', ResetForm::class)->name('reset-password');
});



Route::get('invoice', [InvoiceGenerator::class, 'index'])->name('invoice.generate');
## Prefix Dashboard #

// Admin  and manager routes
Route::middleware(['auth', 'isOnline', 'checkRole:admin,manager'])->group(function () {
    Route::get('/users', Users::class)->name('users');
    Route::get('/users/create', UserForm::class)->name('users.create');
    Route::get('/users/edit/{user}', UserForm::class)->name('users.edit');

    Route::get('/settings/config', Settings::class)->name('settings');
});
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
});

// COMMON
Route::middleware(['auth', 'isOnline', 'isCommon'])->group(function () {
    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('/pos', Pos::class)->name('pos');
    Route::get('/pos/monitor', Monitor::class)->name('pos.monitor');
});
