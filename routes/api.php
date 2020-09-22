<?php

use App\Http\Controllers\API\UserController;
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

/**
 * Router Binding
 */
Route::bind('user', function (string $id) {
	return  MainHasher::decode($id);
});