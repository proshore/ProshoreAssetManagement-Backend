<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InviteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->prefix('users')->group(function () {

    Route::post('register', 'register');

    Route::post('login', 'login');

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', 'logout');

        Route::post('invite', [InviteController::class, 'sendInvite']);

    });

});

