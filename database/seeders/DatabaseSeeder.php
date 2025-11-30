<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear un Usuario Administrador (Para que puedas loguearte)
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'), // password es 'password'
            'role' => 'admin',
        ]);

        // 2. Crear 10 usuarios normales
        User::factory(10)->create();

        // 3. Crear 5 Categorías y 10 Etiquetas
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(10)->create();

        // 4. Crear 20 Eventos
        // Usamos usuarios y categorías aleatorias ya existentes
        Event::factory(20)->make()->each(function ($event) use ($categories, $tags) {
            $event->user_id = User::all()->random()->id;
            $event->category_id = $categories->random()->id;
            $event->save();

            // Llenar la tabla pivote (Relación M:N)
            // A cada evento le asignamos entre 1 y 3 etiquetas aleatorias
            $event->tags()->attach($tags->random(rand(1, 3)));
        });
    }
}
