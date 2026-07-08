<?php

namespace Database\Seeders;

use App\Models\Comentario;
use Illuminate\Database\Seeder;

class ComentarioSeeder extends Seeder
{
    public function run(): void
    {
        Comentario::create([
            'titulo'    => 'Comentario de prueba con payload XSS',
            'contenido' => "Hola, esto es un comentario normal.<br>Y debajo el payload XSS:\n\n<script>alert('XSS')</script>\n\n<b>Este texto en negrita solo se ve si NO escapamos</b>",
            'email'     => 'demo@example.com',
        ]);

        Comentario::create([
            'titulo'    => 'Otro comentario inofensivo',
            'contenido' => 'Este contenido no tiene nada especial. Solo texto plano.',
            'email'     => 'otro@example.com',
        ]);
    }
}
