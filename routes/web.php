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
Route::get('/', 'HomeController@index')->name('index');

Auth::routes();

Route::get('/home', 'HomeController@home')->name('home');
Route::get('/products', 'HomeController@products')->name('user.products');
Route::get('/product/{slug}', 'HomeController@productDetail')->name('product.detail')->where('slug', '([A-z\d\-\/_.]+)');
Route::get('/category/{slug}', 'HomeController@categoryProduct')->name('user.category-product')->where('slug', '([A-z\d\-\/_.]+)');

Route::get('/verify', 'Auth\RegisterController@verifyUser')->name('verify.user');
Route::get('/user/logout', 'Auth\LoginController@userLogout')->name('user.logout');
Route::get('/orders', 'HomeController@orders')->name('user.orders');
Route::get('/orders/{invoice}', 'HomeController@orders')->name('user.orders');

// middlwware guest:admin
// or middlwware auth:admin
Route::prefix('admin')->group(function(){
	Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
	Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
	Route::get('/register', 'Auth\AdminRegisterController@showRegisterForm')->name('admin.register');
	Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');
	Route::get('/', 'AdminController@index')->name('admin.dashboard');
	Route::get('/{path}', 'AdminController@index')->where('path', '([A-z\d\-\/_.]+)');
});