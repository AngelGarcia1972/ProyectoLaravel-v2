<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoDemoController extends Controller
{
    /**
     * ⚠️ DEMO ONLY — INTENTIONALLY VULNERABLE FOR SCHOOL PRACTICE, NEVER USE IN PRODUCTION
     *
     * Construye una consulta SQL concatenando directamente el parámetro del usuario
     * para demostrar cómo funciona la inyección SQL.
     */
    public function buscarVulnerable(Request $request)
    {
        $nombre = $request->query('nombre', '');

        // ⚠️ DEMO ONLY — INTENTIONALLY VULNERABLE FOR SCHOOL PRACTICE, NEVER USE IN PRODUCTION
        $sql = "SELECT * FROM productos WHERE nombre = '" . $nombre . "'";
        $productos = DB::select($sql);

        // Mostramos también la consulta generada para que se vea el efecto
        return view('productos.demo-vulnerable', [
            'productos' => $productos,
            'sql' => $sql,
        ]);
    }
}
