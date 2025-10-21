<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert(
            [
                [
                    'id' => '1',
                    'name' => 'دورة اساسيات اللغة الانجليزية',
                    'section'  => 'انجليزي',
                    'start_date'  => Carbon::parse('2024-09-05'),
                    'end_date'  => Carbon::parse('2024-12-30'),
                    'level'  => 'الأول',
                    'start_time'  => Carbon::createFromFormat('H:i A','8:00 AM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','10:00 AM')->format('Y-m-d H:i:s'),
                    'total_days' => '12',
                    'average_hours' => '2',
                    'total_hours' => '24',
                    'n_d_per_week' => '3',
                    'days' => '["su","tu","th"]',
                    'price' => '50',
                    'currency' => 'USD',
                    'teacher_id' => 6,
                ],
                [
                    'id' => '2',
                    'name' => 'دورة قواعد اللغة',
                    'section'  => 'انجليزي',
                    'start_date'  => Carbon::parse('2024-10-06'),
                    'end_date'  => Carbon::parse('2025-01-30'),
                    'level'  => 'الثاني',
                    'start_time'  => Carbon::createFromFormat('H:i A','1:00 PM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','3:00 PM')->format('Y-m-d H:i:s'),
                    'total_days' => '20',
                    'average_hours' => '2',
                    'total_hours' => '40',
                    'n_d_per_week' => '3',
                    'days' => '["sa","mo","we"]',
                    'price' => '70',
                    'currency' => 'USD',
                    'teacher_id' => 7,
                ],
            ]
        );

    }
}
