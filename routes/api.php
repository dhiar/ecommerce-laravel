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

Route::get('base/show-description', 'API\BaseController@index')->name('base-show-description');
Route::post('base/create', 'API\BaseController@createBase')->name('base-create');
Route::apiResource('base/rekenings', 'API\BaseRekeningController')->names([
    'store' => 'rekening.store'
]);
Route::apiResource('base/slides', 'API\BaseSlideController')->names([
    'store' => 'slide.store'
]);

Route::apiResource('base/socmeds', 'API\BaseSocmedController')->names([
    'store' => 'socmed.store'
]);

Route::apiResource('base/cods', 'API\BaseCodController')->names([
    'store' => 'cod.store'
]);

Route::apiResource('pages', 'API\PageController')->names([
    'store' => 'cod.store'
]);

Route::get('list-province', 'API\ShippingController@listProvince');
Route::get('list-city/{province}', 'API\ShippingController@listCity');
Route::get('list-district/{city}', 'API\ShippingController@listDistrict');

Route::post('upload', [FileController::class, 'upload']);

/**
 * Router Binding
 */
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

Route::post('upload', [FileController::class, 'upload']);