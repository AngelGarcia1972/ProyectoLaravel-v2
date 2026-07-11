<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductoDemoController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

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

/*
 * Rutas de perfil y 2FA
 */
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', function () {
        return view('profile.edit');
    })->name('perfil');

    Route::prefix('perfil/2fa')->name('perfil.2fa.')->group(function () {
        Route::get('/habilitar', [TwoFactorController::class, 'habilitar'])->name('habilitar')->middleware('password.confirm');
        Route::get('/qr', [TwoFactorController::class, 'qr'])->name('qr');
        Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable');
    });

    /*
     * Rutas para demostración de auditoría (Práctica 8)
     */
    Route::post('/productos/{producto}/eliminar', [ProductoController::class, 'destroy'])
        ->name('productos.destroy');

    Route::get('/admin/panel-restringido', function () {
        return view('admin.panel');
    })->middleware('es.admin')->name('admin.panel');
});

/*
 * Sobrescritura de rutas Fortify con throttle adicional
 */
Route::post('/forgot-password', function (Request $request) {
    return app(PasswordResetLinkController::class)->store($request);
})->middleware(['throttle:sensible'])->name('password.email');

Route::post('/reset-password', function (Request $request) {
    return app(NewPasswordController::class)->store($request);
})->middleware(['throttle:sensible'])->name('password.update');
