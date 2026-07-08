<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoDemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
 * Rutas para la práctica de CSRF y XSS
 */
Route::prefix('comentarios')->name('comentarios.')->group(function () {
    Route::get('/', [ComentarioController::class, 'index'])->name('index');
    Route::get('/crear', [ComentarioController::class, 'create'])->name('create');
    Route::post('/', [ComentarioController::class, 'store'])->name('store');

    Route::get('/sin-csrf', [ComentarioController::class, 'createSinProteccion'])->name('sin-csrf');
    Route::post('/sin-csrf', [ComentarioController::class, 'storeSinProteccion'])->name('store.sinCsrf');
});

/*
 * Rutas para la práctica de Inyección SQL
 */
Route::prefix('productos')->name('productos.')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('index');
    Route::get('/buscar', [ProductoController::class, 'buscar'])->name('buscar');
    Route::get('/demo-vulnerable', [ProductoDemoController::class, 'buscarVulnerable'])->name('demo.vulnerable');
});
