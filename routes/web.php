<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UpdatedController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/login', [Authentication::class, 'login'])->name('login');
    Route::post('/login', LoginController::class)->name('login.store');

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/logout', LogoutController::class)->name('logout');
        Route::put('/update/{userid}', UpdatedController::class);

        Route::view('/register', 'admin.register')->name('register');
        Route::post('/register', RegisterController::class)->name('register.store');

        Route::get('/dashboard', [AdminController::class, 'index'])->name('index');

        Route::resource('/category', CategoryController::class);

        Route::resource('/product', ProductController::class);

        Route::resource('/order', OrderController::class)->only(['index', 'create', 'show']);
    });
});