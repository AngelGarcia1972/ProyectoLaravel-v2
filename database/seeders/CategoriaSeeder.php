<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::create(['id' => 1, 'nombre' => 'Cómputo', 'slug' => 'computo']);
        Categoria::create(['id' => 2, 'nombre' => 'Periféricos', 'slug' => 'perifericos']);
        Categoria::create(['id' => 3, 'nombre' => 'Accesorios', 'slug' => 'accesorios']);
    }
}
