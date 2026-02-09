<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    LoginController,
    ResetPasswordController,
    SiteSettingController,
    SubAdminController,
    CustomerController,
    OrderController,
    ProductController,
    ProductReportController,
    GodownController,
    ImportController,
    ProductCartController,
    LoginDetailsController,
};
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

Route::redirect('/','login');
Route::get('login',[LoginController::class,'loginView'])->name('login');
Route::post('login',[LoginController::class,'login'])->name('login');
Route::get('forgot-password',[ResetPasswordController::class,'forgotPassword'])->name('forgot_password');
Route::post('forgot-password',[ResetPasswordController::class,'forgotPasswordSave'])->name('forgot_password_save');
Route::get('reset-password',[ResetPasswordController::class,'resetPassword'])->name('reset_password');
Route::post('reset-password',[ResetPasswordController::class,'resetPasswordSave'])->name('reset_password_save');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout',[LoginController::class,'logout'])->name('logout');
    Route::get('dashboard',[SiteSettingController::class,'dashboard'])->name('dashboard');
Route::group(['middleware'=> 'user.permission'], function(){
Route::get('site-settings',[SiteSettingController::class,'siteSettings'])->name('site.settings');
/* Route::get('dashoard',[SiteSettingController::class,'dashboard'])->name('dashboard'); */
Route::get('change-password',[SiteSettingController::class,'changePassword'])->name('change_password');
/*Godown*/
Route::get('godown-stocks',[GodownController::class,'godownStock'])->name('godown_stock');

Route::get('products',[ProductController::class,'productList'])->name('product_index');
Route::get('products/order/{order_id}',[ProductController::class,'productOrder'])->name('product.order');
Route::get('limited-products-list',[ProductController::class,'limitedProduct'])->name('limited_product');
Route::get('out-of-products',[ProductController::class,'outOfProduct'])->name('out_of_product');
Route::get('uploda-products-image',[ProductController::class,'uplodaProductImage'])->name('upload_product_image');

Route::resources([
    'sub-admins' => SubAdminController::class,
]);

Route::get('customers-list',[CustomerController::class,'customerList'])->name('customer_list');
Route::get('customers',[CustomerController::class,'index'])->name('customer.index');
Route::get('customers/{contact}',[CustomerController::class,'customerDetails'])->name('customer.details');
Route::get('customers-list/{contact}',[CustomerController::class,'customerView'])->name('customer_view');
Route::get('customers-card-list',[CustomerController::class,'customerCardList'])->name('customer_card_list');
Route::get('orders-list',[OrderController::class,'orderList'])->name('order_list');
Route::get('daily-order-report',[OrderController::class,'orderReport'])->name('order_report');
Route::get('sub-admin-orders',[OrderController::class,'subAdminOrder'])->name('sub_admin_report');
Route::get('sub-admin-orders/{id}',[OrderController::class,'subAdminOrderView'])->name('sub_admin_orders.view');
Route::get('login-details',[LoginDetailsController::class,'loginDetails'])->name('loginDetails');

Route::get('product-report',[ProductReportController::class,'productReport'])->name('product_report');
Route::get('stock-report',[ProductReportController::class,'stockReport'])->name('stock_report');
Route::get('stock-inserted',[ProductReportController::class,'stockInserted'])->name('stock_inserted');
Route::get('product-selling-order',[ProductReportController::class,'productSellingOrder'])->name('product_selling_order');
Route::get('purchase-report',[ProductReportController::class,'purchaseReport'])->name('purchase_report');
Route::get('user-purchase-report',[ProductReportController::class,'userPurchaseReport'])->name('user_purchase_report');
Route::get('due-order-report',[ProductReportController::class,'dueOrderReport'])->name('due_order_report');


Route::get('products/add-to-cart',[ProductCartController::class,'addToCart'])->name('add_to_cart');


Route::get('import',[ImportController::class,'import'])->name('import');
Route::post('import',[ImportController::class,'importData'])->name('import.data');
});
});

Route::get('web-test', function () {
    dd('okkdd');
 });













Route::get('data-insert',[LoginController::class,'dataInsert']);

Route::get('clear', function () {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');
    return 'Cleared.';
});
