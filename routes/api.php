<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\RegisterUserController;

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
Route::post('register-admin',[AdminController::class,'registerAdmin']);
Route::post('login-admin',[AdminController::class,'loginAdmin']);
Route::apiResource("register-user",RegisterUserController::class);


//protected Route
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout-admin',[AdminController::class,'logoutAdmin']);
});

