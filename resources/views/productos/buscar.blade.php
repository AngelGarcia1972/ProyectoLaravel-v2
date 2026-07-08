<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Búsqueda Segura</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 0 auto; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        th { background: #f5f5f5; }
        .filters { background: #f9f9f9; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .filters label { margin-right: 0.5rem; }
        .filters input, .filters button { padding: 0.35rem 0.6rem; margin-right: 0.5rem; }
        .pagination { margin-top: 1rem; }
        .pagination a { padding: 0.25rem 0.5rem; margin: 0 0.15rem; border: 1px solid #ccc; text-decoration: none; color: #333; }
        .info { background: #d1ecf1; border: 1px solid #bee5eb; padding: 0.75rem; border-radius: 0.25rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <h1>Búsqueda de Productos</h1>

    <div class="info">
        <strong>Endpoint seguro</strong> — Las consultas usan Eloquent con parámetros vinculados.
        No es vulnerable a inyección SQL.
    </div>

    <form class="filters" method="GET" action="{{ route('productos.buscar') }}">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="{{ request('nombre') }}" placeholder="Ej: Laptop">

        <label>Precio máx:</label>
        <input type="number" step="0.01" name="precio_max" value="{{ request('precio_max') }}" placeholder="Ej: 5000">

        <label>Categoría (IDs separados por coma):</label>
        <input type="text" name="categoria" value="{{ request('categoria') }}" placeholder="Ej: 1,2">

        <button type="submit">Buscar</button>
        <a href="{{ route('productos.buscar') }}">Limpiar</a>
    </form>

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
                <tr><td colspan="5">No se encontraron productos con esos filtros.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $productos->links() }}
    </div>

    <p><a href="{{ route('productos.index') }}">← Volver al listado completo</a></p>
</body>
</html>
