<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Diploma;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            DataStudentSeeder::class,
            DiplomaSeeder::class,
            CourseSeeder::class,
            DiplomaCourseSeeder::class,
            RevenueSeeder::class,
            ExpenseSeeder::class,
            CustomerSeeder::class,
            TranslationDelSeeder::class,
            
        ]);
    }
}
