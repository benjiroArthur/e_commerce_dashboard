<?php

use App\Models\UserType;
use Illuminate\Support\Facades\Route;
use function Spatie\array_rand_value;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect()->to('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*user routes*/
Route::prefix('system')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('/restore-user/{id}', [App\Http\Controllers\UserController::class, 'restoreUser'])->name('restore-user');
    Route::post('/user-bulk-delete', [App\Http\Controllers\UserController::class, 'bulkDestroy'])->name('user-bulk-delete');
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::post('/products-image-upload', [App\Http\Controllers\ProductController::class, 'uploadProductImage'])->name('products-image-upload');
    Route::resource('orders', App\Http\Controllers\OrderController::class);
    Route::resource('orders-history', App\Http\Controllers\OrderController::class);
    Route::resource('payments', App\Http\Controllers\PaymentController::class);
    Route::resource('order-items', App\Http\Controllers\OrderItemController::class);
    Route::resource('discount-product', App\Http\Controllers\DiscountProductController::class);
});


/*Configurations routes*/
Route::prefix('configurations')->group(function () {
    Route::resource('status', App\Http\Controllers\StatusController::class);
    Route::resource('product-category', App\Http\Controllers\ProductCategoryController::class);    Route::post('/user-bulk-delete', [App\Http\Controllers\UserController::class, 'bulkDestroy'])->name('user-bulk-delete');
    Route::resource('price-grouping', App\Http\Controllers\PriceGroupingsController::class);
    Route::resource('payment-type', App\Http\Controllers\PaymentTypeController::class);
    Route::resource('discounts', App\Http\Controllers\DiscountController::class);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
