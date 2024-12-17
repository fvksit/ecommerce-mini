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
    Route::post('/login', [Authentication::class, 'login']);

    route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::get('/me', [MeController::class, 'index']);
        Route::get('/me//', [MeController::class, 'show']);
        Route::post('/logout', [Authentication::class, 'logout']);
        Route::put('/update/{userid}', [Authentication::class, 'update']);
        Route::post('/register', [Authentication::class, 'register']);
        Route::resource('/category', CategoryController::class);
    });
});
