<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\EmployeeVendorController;
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
//Admin Register Login and Logout
Route::controller(AdminController::class)->prefix('admin')->group(function (){
    Route::post('register', 'registerAdmin');
    Route::post('login','loginAdmin');
    Route::post('logout', 'logoutAdmin')->middleware('auth:sanctum');
});

//Route For The Data related To Vendor & Employee
Route::controller(EmployeeVendorController::class)->prefix('admin')->group(function (){
    Route::get('all-user', 'viewAllUsers');
    Route::post('view-user-roles/{id}', 'viewUserRole');
    Route::delete('delete-user/{id}', 'deleteUser');
    Route::post('invite', 'InviteOther');
});


Route::controller(InviteController::class)->prefix('invite')->group(function (){
    Route::get('invited-users', 'listOfInvitedUsers');
    Route::post('resend', 'reInvite');
    Route::delete('revoke/{id}', 'revoke');

});

