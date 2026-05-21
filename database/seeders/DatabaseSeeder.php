<?php

namespace Database\Seeders;

use App\Models\User;
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
        // On crée un candidat par défaut
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'candidat',
        ]);

        // On appelle le seeder d'offres d'emploi
        $this->call(JobSeeder::class);
    }
}
