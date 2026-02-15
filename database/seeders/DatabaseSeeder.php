<?php

namespace Database\Seeders;
use App\Models\Course;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Database\Factories\StudentFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(UserSeeder::class);
       Course::factory()->count(10)->create();
       User::factory(10)->create();
    }
}
