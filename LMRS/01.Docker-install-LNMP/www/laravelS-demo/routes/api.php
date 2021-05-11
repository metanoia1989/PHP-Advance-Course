<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'middleware' => ['bindines'],
    'namespace' => 'App\Http\Controller\Api\\V1',
], function ($api) {
    $api->post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');
    $api->post('users', 'UserController@store')->name('user.store');
    $api->post('login', 'UserLoginController@store')->name('login.store');
});