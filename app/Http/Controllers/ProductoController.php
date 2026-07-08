<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('id')->paginate(15);
        return view('productos.index', compact('productos'));
    }

    public function buscar(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', $request->precio_max);
        }

        if ($request->filled('categoria')) {
            $categorias = explode(',', $request->categoria);
            $query->whereIn('categoria_id', $categorias);
        }

        $productos = $query->orderBy('id')->paginate(15)->appends($request->query());

        if ($request->wantsJson()) {
            return $productos;
        }

        return view('productos.buscar', compact('productos'));
    }
}
