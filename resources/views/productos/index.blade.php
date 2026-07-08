<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos - Listado</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 900px; margin: 0 auto; padding: 2rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: left; }
        th { background: #f5f5f5; }
        .pagination { margin-top: 1rem; }
        .pagination a { padding: 0.25rem 0.5rem; margin: 0 0.15rem; border: 1px solid #ccc; text-decoration: none; color: #333; }
    </style>
</head>
<body>
    <h1>Listado de Productos</h1>

    <p><a href="{{ route('productos.buscar') }}">Buscar productos</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría ID</th>
                <th>Activo</th>
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
                    <td>{{ $producto->activo ? 'Sí' : 'No' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No hay productos registrados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $productos->links() }}
    </div>
</body>
</html>
