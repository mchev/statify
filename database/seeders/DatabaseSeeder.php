<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->withPersonalTeam()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => '$2y$10$blavSXjEsdOIgL/6ynZwoelinRUNkRtQXrkPTjb3XO/8Lg4Ok8dq6', // admin
        ]);

        \App\Models\Website::factory(1)->create();
    }
}
