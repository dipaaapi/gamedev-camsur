<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Developer',
            'username' => 'developer',
            'email' => 'developer@example.com',
            'mobile_number' => '09123456789',
            'password' => 'password',
            'users_level' => 0,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'mobile_number' => '09987654321',
            'password' => 'password',
            'users_level' => 1,
        ]);

        $this->call(CareerSeeder::class);
        $this->call(PublicInfoSeeder::class);
        $this->call(AssessmentQuestionSeeder::class);
    }
}
