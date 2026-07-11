<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/password', [ApiAuthController::class, 'updatePassword']);

    Route::get('/productos', [ProductoController::class, 'index']);
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy']);

    Route::middleware('es.admin')->group(function () {
        Route::get('/admin', function () {
            return response()->json(['mensaje' => 'Panel de administración']);
        });
    });
});
