<?php

use App\Http\Controllers\AuthController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'azure:guest'], function () {
    Route::get('/login', function () {
        return view('login');
    })->name('login');
    Route::get('/azure-login', [AuthController::class, 'signIn'])->name('azure_login');
    Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
});

Route::group(['middleware' => 'azure:auth'], function () {
    Route::get('/admin', [AuthController::class, 'home'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
