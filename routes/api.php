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

Route::get('findUser', 'API\UserController@search');
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');

Route::get('base/show-description', 'API\BaseController@index')->name('base-show-description');
Route::post('base/create-description', 'API\BaseController@createDescription')->name('base-create-description');
Route::apiResource('base/rekenings', 'API\BaseRekeningController')->names([
    'store' => 'rekening.store'
]);
Route::apiResource('base/slides', 'API\BaseSlideController')->names([
    'store' => 'slide.store'
]);
// base/slides

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

Route::post('upload', [FileController::class, 'upload']);