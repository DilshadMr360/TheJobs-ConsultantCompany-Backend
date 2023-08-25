<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
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

// Public Routes
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::resource('/countries', CountryController::class)->only(['index']);
Route::resource('/jobs',JobController::class)->only(['index']);;

// Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('/countries', CountryController::class)->except(['index']);
    Route::resource('/jobs',JobController::class)->except(['index']);;
    Route::resource('/appointments', AppointmentController::class);
    Route::resource('/users', UserController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::group(['middleware' => ['role:client']], function () {

    });

    Route::group(['middleware' => ['role:consultant']], function () {


    });

    Route::group(['middleware' => ['role:admin']], function () {


    });
});
