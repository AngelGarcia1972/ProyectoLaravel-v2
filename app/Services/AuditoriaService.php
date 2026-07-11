<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AuditoriaService
{
    public static function log(string $accion, array $datos = []): void
    {
        Log::channel('audit')->info($accion, array_merge([
            'usuario_id' => auth()->id() ?? 'anonimo',
            'usuario' => auth()->user()?->email ?? 'anonimo',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'metodo' => request()->method(),
            'timestamp' => now()->toIso8601String(),
        ], $datos));
    }

    public static function security(string $evento, string $nivel = 'warning', array $datos = []): void
    {
        Log::channel('security')->{$nivel}($evento, array_merge([
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
        ], $datos));
    }
}
