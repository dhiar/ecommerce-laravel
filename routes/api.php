<?php

use App\Http\Controllers\API\{UserController, FileController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use App\Hashers\MainHasher;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:admin-api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('user', 'API\UserController')->names([
    'store' => 'user.store'
]);

Route::get('bitly', 'API\BaseController@bitly');

Route::get('findUser', 'API\UserController@search');
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::put('admin-rekening/{admin}', 'API\UserController@updateRekening');

Route::get('base/show-description', 'API\BaseController@index')->name('base-show-description');
Route::post('base/create', 'API\BaseController@createBase')->name('base-create');
Route::apiResource('base/rekenings', 'API\BaseRekeningController')->names([
    'store' => 'rekening.store'
]);
Route::apiResource('base/slides', 'API\BaseSlideController')->names([
    'store' => 'slide.store'
]);
Route::put('base/clone-slide/{slide}', 'API\BaseSlideController@cloneSlide');

Route::apiResource('base/socmeds', 'API\BaseSocmedController')->names([
    'store' => 'socmed.store'
]);

Route::apiResource('base/cods', 'API\BaseCodController')->names([
    'store' => 'cod.store'
]);

Route::apiResource('pages', 'API\PageController')->names([
    'store' => 'page.store'
]);

Route::apiResource('navigations', 'API\NavigationController')->names([
    'store' => 'navigation.store'
]);

Route::apiResource('footers', 'API\FooterController')->names([
    'store' => 'footer.store'
]);

Route::apiResource('testimony', 'API\TestimonyController')->names([
	'store' => 'testimony.store'
]);

Route::apiResource('product_brand', 'API\ProductBrandController')->names([
	'store' => 'product_brand.store'
]);

Route::get('product_brand/category/{category}', 'API\ProductBrandController@listBrandsCategory');

Route::put('clone-brand/{product_brand}', 'API\ProductBrandController@cloneBrand');

Route::apiResource('product_category', 'API\ProductCategoryController')->names([
	'store' => 'product_category.store'
]);
Route::put('clone-category/{product_category}', 'API\ProductCategoryController@cloneCategory');

Route::apiResource('products', 'API\ProductController')->names([
	'store' => 'product.store'
]);
Route::get('products/{product}/images', 'API\ProductController@listImages');
Route::get('products/{product}/grosirs', 'API\ProductController@listGrosirs');
Route::put('clone-product/{product}', 'API\ProductController@cloneProduct');
Route::post('search-tags', 'API\ProductController@searchTags')->name('search.tags');
Route::get('tags', 'API\ProductController@searchTags')->name('all.tags');
Route::post('filter-products', 'API\ProductController@filterProducts')->name('filter.products');

Route::apiResource('grosirs', 'API\GrosirController')->names([
	'store' => 'grosir.store'
]);
Route::apiResource('product_images', 'API\ProductImageController')->names([
	'store' => 'product_images.store'
]);

Route::apiResource('promos', 'API\PromoController')->names([
	'store' => 'promo.store'
]);

Route::apiResource('transactions', 'API\TransactionController')->names([
	'store' => 'transaction.store'
]);

Route::get('transactions-by-invoice', 'API\TransactionController@indexByInvoice');

Route::apiResource('address', 'API\AddressController')->names([
	'store' => 'address.store'
]);

Route::get('list-province', 'API\ShippingController@listProvince');
Route::get('list-city/{province}', 'API\ShippingController@listCity');
Route::get('list-district/{city}', 'API\ShippingController@listDistrict');

Route::get('show-province/{id}', 'API\ShippingController@showProvince');
Route::get('show-city/{id}', 'API\ShippingController@showCity');
Route::get('show-district/{id}', 'API\ShippingController@showDistrict');

Route::post('upload', [FileController::class, 'upload']);
Route::post('upload-product', [FileController::class, 'uploadProduct']);
Route::post('upload-slide', [FileController::class, 'uploadSlide']);
Route::post('upload-category', [FileController::class, 'uploadCategory']);
Route::post('upload-brand', [FileController::class, 'uploadBrand']);
Route::post('upload-payment', [FileController::class, 'uploadPayment']);

/**
 * Router Binding
 */
Route::bind('admin', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('address', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('user', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('rekening', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('slide', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('socmed', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('cod', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('page', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('page_id', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('navigation', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('navigation_id', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('footer', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('testimony', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('product_category', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('product_brand', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('product_images', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('product', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('id_product', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('category', function (string $id) {
	return is_numeric($id) ? $id : MainHasher::decode($id);
});

Route::bind('id_category', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('brand', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('id_brand', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('promo', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('grosir', function (string $id) {
	return  MainHasher::decode($id);
});

Route::bind('transaction', function (string $id) {
	return  MainHasher::decode($id);
});

Route::post('upload', [FileController::class, 'upload']);