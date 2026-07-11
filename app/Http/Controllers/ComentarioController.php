<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function index()
    {
        $comentarios = Comentario::latest()->get();

        return view('comentarios.index', compact('comentarios'));
    }

    public function create()
    {
        return view('comentarios.create');
    }

    /*
     * DEMO ONLY — muestra el formulario que POSTea a la ruta sin CSRF y sin strip_tags.
     */
    public function createSinProteccion()
    {
        $comentarios = Comentario::latest()->get();

        return view('comentarios.create-sin-csrf', compact('comentarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:200', 'regex:/^[\w\sáéíóúüñÁÉÍÓÚÜÑ,.\-!¡¿?()]+$/u'],
            'contenido' => ['required', 'string', 'max:5000'],
            'email' => ['required', 'email:rfc,dns'],
        ]);

        $comentario = Comentario::create([
            'titulo' => strip_tags($validated['titulo']),
            'contenido' => strip_tags($validated['contenido']),
            'email' => $validated['email'],
        ]);

        /*
         * NOTA (SOLO PARA DEMO / PRÁCTICA):
         * Adicionalmente guardamos una version "raw" del contenido (sin strip_tags)
         * para poder demostrar XSS almacenado en la ruta de prueba aislada.
         * Esto JAMÁS existiría en código de producción real.
         *
         * La ruta comentarios.storeSinCsrf (sin CSRF) leerá esta versión raw.
         */
        $comentario->raw_version = $request->input('contenido');

        return redirect()->route('comentarios.index')
            ->with('success', 'Comentario creado con protección (CSRF + strip_tags).');
    }

    /*
     * DEMO ONLY — para práctica escolar, no usar en producción.
     *
     * Este método:
     * 1. No verifica token CSRF (la ruta lo excluye del middleware)
     * 2. No aplica strip_tags al contenido (XSS intencional)
     */
    public function storeSinProteccion(Request $request)
    {
        $comentario = Comentario::create([
            'titulo' => $request->input('titulo'),
            'contenido' => $request->input('contenido'),
            'email' => $request->input('email'),
        ]);

        $comentario->raw_version = $request->input('contenido');

        return redirect()->route('comentarios.sin-csrf')
            ->with('success', 'Comentario creado SIN protección (demo) — contenido vulnerable almacenado.');
    }
}
