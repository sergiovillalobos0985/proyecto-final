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
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'), // password es 'password'
            'role' => 'admin',
        ]);
        User::factory(10)->create();
        $categories = Category::factory(5)->create();
        $tags = Tag::factory(10)->create();

        Event::factory(20)->make()->each(function ($event) use ($categories, $tags) {
            $event->user_id = User::all()->random()->id;
            $event->category_id = $categories->random()->id;
            $event->save();

            $event->tags()->attach($tags->random(rand(1, 3)));
        });
    }
}
