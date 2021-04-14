<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','HomeController@index')->name('home');

Route::get('/category','CategoryController@index')->name('category');
Route::get('/category/{id}','CategoryController@detail')->name('category-detail');

Route::get('/detail/{id}','DetailController@index')->name('detail');
Route::post('/detail/add/{id}','DetailController@add')->name('detail-add');

Route::post('/checkout/callback','CheckoutController@callback')->name('midtrans-callback');

Route::get('/success','CartController@success')->name('success');

Route::get('/register/success','Auth\RegisterController@success')
->name('register-success');




// Middleware Auth------------
Route::group(['middleware'=>['auth']],function(){

    Route::get('/cart','CartController@index')->name('cart');
    Route::delete('/cart/delete/{id}','CartController@delete')->name('cart-delete');

    Route::post('/checkout','CheckoutController@process')->name('checkout');

    Route::get('/dashboard','DashboardController@index')->name('dashboard');

    Route::get('/dashboard/products','DashboardProductsController@index')
    ->name('dashboard-products');
    Route::get('/dashboard/products/create','DashboardProductsController@create')
    ->name('dashboard-products-create');
    Route::post('/dashboard/products/store','DashboardProductsController@store')
    ->name('dashboard-products-store');
    Route::get('/dashboard/products/details/{id}','DashboardProductsController@details')
    ->name('dashboard-products-details');
    Route::post('/dashboard/products/update/{id}','DashboardProductsController@update')
    ->name('dashboard-products-update');
    
    Route::post('/dashboard/products/gallery/upload','DashboardProductsController@uploadGallery')
    ->name('dashboard-products-gallery-upload');
    Route::get('/dashboard/products/gallery/delete/{id}','DashboardProductsController@deleteGallery')
    ->name('dashboard-products-gallery-delete');

    Route::get('/dashboard/transactions','DashboardTransactionsController@index')
    ->name('dashboard-transactions');
    Route::get('/dashboard/transactions/details/{id}','DashboardTransactionsController@details')
    ->name('dashboard-transactions-details');
    Route::post('/dashboard/transactions/update/{id}','DashboardTransactionsController@update')
    ->name('dashboard-transactions-update');

    Route::get('/dashboard/setting/store','DashboardSettingController@store')
    ->name('dashboard-setting-store');
    Route::get('/dashboard/setting/account','DashboardSettingController@account')
    ->name('dashboard-setting-account');
    Route::post('/dashboard/setting/update/{redirect}','DashboardSettingController@update')
    ->name('dashboard-setting-update');

});

// Middleware Auth khusus ADMIN------------
// prefix = http://bwastore-laravel.test/admin/
// namespace = folder Admin pada controller
Route::prefix('admin')
    ->namespace('Admin')
    ->middleware(['auth','admin'])
    ->group(function(){
        Route::get('/','DashboardController@index')->name('admin-dashboard');
        Route::resource('category', 'CategoryController');
        Route::resource('user', 'UserController');
        Route::resource('product', 'ProductController');
        Route::resource('product-gallery', 'ProductGalleryController');
    });

Auth::routes();




