<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{AuthController, InviteController};

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

    Route::post('forgot-password', 'forgotPassword');

    Route::post('reset-password', 'resetPassword');

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', 'logout');

        Route::controller(InviteController::class)->group(function () {

            Route::post('invite', 'sendInvite');

            Route::get('invite', 'listInvited');

            Route::post('invite/resend/{id}', 'sendReinvite');

            Route::get('invite/revoke/{id}', 'revoke');

        });

    });

});

