<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UpdatedController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('/login', LoginController::class);
    Route::post('/register', RegisterController::class);

    Route::middleware('auth:sanctum', 'user')->group(function () {
        Route::get('/me', [MeController::class, 'show']);
        Route::post('/logout', LogoutController::class);
        Route::put('/update/{userid}', UpdatedController::class);
    });
});
