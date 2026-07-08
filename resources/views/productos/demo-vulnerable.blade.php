<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DEMO: Inyección SQL (Vulnerable)</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 0 auto; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        th { background: #f5f5f5; }
        .warning { background: #f8d7da; border: 2px solid #f5c6cb; color: #721c24; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-weight: bold; text-align: center; }
        .sql-box { background: #272822; color: #f8f8f2; padding: 0.75rem; border-radius: 0.25rem; font-family: monospace; margin: 1rem 0; overflow-x: auto; }
        .form-group { margin: 1rem 0; }
        .form-group label { display: block; margin-bottom: 0.25rem; font-weight: bold; }
        .form-group input[type="text"] { width: 100%; padding: 0.5rem; font-family: monospace; box-sizing: border-box; }
        button { padding: 0.5rem 1.5rem; cursor: pointer; background: #dc3545; color: white; border: none; border-radius: 0.25rem; font-size: 1rem; }
        .peligro { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="warning">
        ⚠️ DEMO: Inyección SQL (endpoint vulnerable, solo para práctica)
    </div>

    <p class="peligro">
        Este endpoint construye la consulta SQL concatenando directamente la entrada del usuario.
        <strong>NUNCA uses este patrón en producción.</strong>
    </p>

    <form method="GET" action="{{ route('productos.demo.vulnerable') }}">
        <div class="form-group">
            <label for="nombre">Nombre del producto a buscar:</label>
            <input type="text" name="nombre" id="nombre" value="{{ request('nombre') }}" placeholder="Ej: Laptop — o intenta: ' OR '1'='1">
        </div>
        <button type="submit">Buscar (vulnerable)</button>
    </form>

    @if (isset($sql))
        <h3>Consulta SQL generada:</h3>
        <div class="sql-box">{{ $sql }}</div>
    @endif

    <h3>Resultados ({{ count($productos) }})</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría ID</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>{{ $producto->stock }}</td>
                    <td>{{ $producto->categoria_id ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No se encontraron productos.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p><a href="{{ route('productos.buscar') }}">Ir a la búsqueda segura →</a></p>
</body>
</html>
