<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use laravel\Models\User;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Client::factory(10)->create();

        User::factory()->create([
            'name' => 'Test Client',
            'email' => 'test@example.com',
        ]);
    }
}
