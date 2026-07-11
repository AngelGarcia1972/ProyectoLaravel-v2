<?php

namespace App\Http\Middleware;

use App\Services\AuditoriaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->rol !== 'admin') {
            AuditoriaService::security('ACCESO_DENEGADO', 'warning', [
                'usuario_id' => $request->user()?->id ?? 'anonimo',
                'usuario' => $request->user()?->email ?? 'anonimo',
                'ruta_solicitada' => $request->path(),
                'metodo' => $request->method(),
            ]);

            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Acceso denegado: se requiere rol admin'], 403);
            }

            return redirect()->route('perfil')
                ->with('error', 'Acceso denegado: se requiere rol de administrador.');
        }

        return $next($request);
    }
}
