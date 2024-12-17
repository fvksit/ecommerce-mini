<?php

use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Authentication;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
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

Route::prefix('admin')->group(function () {
    Route::get('/me', [MeController::class, 'index']);
    Route::post('/login', [Authentication::class, 'Login']);
    Route::post('/register', [Authentication::class, 'Register']);
    Route::post('/logout', [Authentication::class, 'Logout']);
    Route::put('/update/{userid}', [Authentication::class, 'Update']);

    route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('/category', CategoryController::class);
    });
});
