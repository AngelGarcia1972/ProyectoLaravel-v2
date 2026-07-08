<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            ['nombre' => 'Laptop Gamer X1', 'descripcion' => 'Laptop con RTX 4060 y 32GB RAM', 'precio' => 25999.00, 'stock' => 10, 'categoria_id' => 1],
            ['nombre' => 'Teclado Mecánico RGB', 'descripcion' => 'Teclado mecánico switches Cherry MX', 'precio' => 1899.00, 'stock' => 50, 'categoria_id' => 2],
            ['nombre' => 'Mouse Inalámbrico Pro', 'descripcion' => 'Mouse ergonómico 8000 DPI', 'precio' => 1299.00, 'stock' => 35, 'categoria_id' => 2],
            ['nombre' => 'Monitor 27" 4K', 'descripcion' => 'Monitor IPS 4K UHD 60Hz', 'precio' => 8499.00, 'stock' => 15, 'categoria_id' => 1],
            ['nombre' => 'Audífonos Bluetooth', 'descripcion' => 'Audífonos cancelación de ruido', 'precio' => 2499.00, 'stock' => 25, 'categoria_id' => 3],
            ['nombre' => 'Webcam HD 1080p', 'descripcion' => null, 'precio' => 899.00, 'stock' => 40, 'categoria_id' => 3],
            ['nombre' => 'Hub USB-C 7 en 1', 'descripcion' => 'Hub multipuerto con HDMI y PD', 'precio' => 699.00, 'stock' => 60, 'categoria_id' => 2],
            ['nombre' => 'Silla Ergonómica Pro', 'descripcion' => 'Silla de oficina ajustable lumbar', 'precio' => 7999.00, 'stock' => 8, 'categoria_id' => null],
            ['nombre' => 'Tablet Drawing Pad', 'descripcion' => 'Tableta gráfica 10x6 pulgadas', 'precio' => 3199.00, 'stock' => 20, 'categoria_id' => 1],
            ['nombre' => 'Cargador Portátil 20000mAh', 'descripcion' => null, 'precio' => 599.00, 'stock' => 100, 'categoria_id' => 3],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
