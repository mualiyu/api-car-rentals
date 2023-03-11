<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return 'Server is Active!';
});

# Admins
Route::prefix('admin')->group(function () {
    # register
    Route::post('register', [\App\Http\Controllers\UserController::class, 'register']);
    
    # verify
    Route::post('verify', [\App\Http\Controllers\UserController::class, 'verify']);
    
    # login
    Route::post('login', [\App\Http\Controllers\UserController::class, 'login']);
    
    # recover
    Route::post('recover', [\App\Http\Controllers\UserController::class, 'recover']);
    
    # reset
    Route::post('reset', [\App\Http\Controllers\UserController::class, 'reset']);
    
    Route::middleware('auth:sanctum')->get('profile', [\App\Http\Controllers\UserController::class, 'user']);
});


# Riders
Route::prefix('rider')->group(function () {
    # register
    Route::middleware('auth:sanctum')->post('register', [\App\Http\Controllers\RiderController::class, 'register']);
    
    # verify
    Route::post('verify', [\App\Http\Controllers\RiderController::class, 'verify']);
    
    # login
    Route::post('login', [\App\Http\Controllers\RiderController::class, 'login']);
    
    # recover
    Route::post('recover', [\App\Http\Controllers\RiderController::class, 'recover']);
    
    # reset
    Route::post('reset', [\App\Http\Controllers\RiderController::class, 'reset']);
    
    Route::middleware('auth:sanctum')->get('profile', [\App\Http\Controllers\RiderController::class, 'user']);
});


# Customers
Route::prefix('customer')->group(function () {
    # register
    Route::post('register', [\App\Http\Controllers\CustomerController::class, 'register']);
    
    # verify
    Route::post('verify', [\App\Http\Controllers\CustomerController::class, 'verify']);
    
    # login
    Route::post('login', [\App\Http\Controllers\CustomerController::class, 'login']);
    
    # recover
    Route::post('recover', [\App\Http\Controllers\CustomerController::class, 'recover']);
    
    # reset
    Route::post('reset', [\App\Http\Controllers\CustomerController::class, 'reset']);
    
    Route::middleware('auth:sanctum')->get('profile', [\App\Http\Controllers\CustomerController::class, 'user']);
});


