<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DiplomaCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert(
            [
                [
                    'id' => '3',
                    'name' => 'دورة اساسيات اللغة الاسبانية',
                    'section'  => 'اسباني',
                    'start_date'  => Carbon::parse('2024-11-05'),
                    'end_date'  => Carbon::parse('2025-01-30'),
                    'level'  => 'الأول',
                    'start_time'  => Carbon::createFromFormat('H:i A','10:00 AM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','12:00 PM')->format('Y-m-d H:i:s'),
                    'total_days' => '12',
                    'average_hours' => '2',
                    'total_hours' => '24',
                    'n_d_per_week' => '3',
                    'days' => '["su","tu","th"]',
                    'price' => '50',
                    'currency' => 'USD',
                    'teacher_id' => 6,
                    'diploma_id' => 1,
                ],
                [
                    'id' => '4',
                    'name' => 'دورة قواعد اللغة الاسبانية',
                    'section'  => 'اسباني',
                    'start_date'  => Carbon::parse('2024-02-05'),
                    'end_date'  => Carbon::parse('2025-02-30'),
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
                    'diploma_id' => 1,
                ],
                [
                    'id' => '5',
                    'name' => 'دورة اساسيات اللغة الفرنسية',
                    'section'  => 'فرنسي',
                    'start_date'  => Carbon::parse('2024-11-05'),
                    'end_date'  => Carbon::parse('2025-03-30'),
                    'level'  => 'الأول',
                    'start_time'  => Carbon::createFromFormat('H:i A','14:00 PM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','16:00 PM')->format('Y-m-d H:i:s'),
                    'total_days' => '12',
                    'average_hours' => '2',
                    'total_hours' => '24',
                    'n_d_per_week' => '3',
                    'days' => '["su","tu","th"]',
                    'price' => '85',
                    'currency' => 'USD',
                    'teacher_id' => 6,
                    'diploma_id' => 2,
                ],
                [
                    'id' => '6',
                    'name' => 'دورة قواعد اللغة الفرنسية',
                    'section'  => 'فرنسي',
                    'start_date'  => Carbon::parse('2025-04-01'),
                    'end_date'  => Carbon::parse('2025-04-25'),
                    'level'  => 'الثاني',
                    'start_time'  => Carbon::createFromFormat('H:i A','1:00 PM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','3:00 PM')->format('Y-m-d H:i:s'),
                    'total_days' => '20',
                    'average_hours' => '2',
                    'total_hours' => '40',
                    'n_d_per_week' => '3',
                    'days' => '["sa","mo","we"]',
                    'price' => '75',
                    'currency' => 'USD',
                    'teacher_id' => 7,
                    'diploma_id' => 2,
                ],
                [
                    'id' => '7',
                    'name' => 'دورة صوتيات فرنسي',
                    'section'  => 'فرنسي',
                    'start_date'  => Carbon::parse('2025-01-10'),
                    'end_date'  => Carbon::parse('2025-04-15'),
                    'level'  => 'الأول',
                    'start_time'  => Carbon::createFromFormat('H:i A','6:00 AM')->format('Y-m-d H:i:s'),
                    'end_time' => Carbon::createFromFormat('H:i A','8:00 AM')->format('Y-m-d H:i:s'),
                    'total_days' => '12',
                    'average_hours' => '2',
                    'total_hours' => '24',
                    'n_d_per_week' => '3',
                    'days' => '["su","tu","th"]',
                    'price' => '130',
                    'currency' => 'USD',
                    'teacher_id' => 7,
                    'diploma_id' => 2,
                ],
            ]
        );
    }
}
