<?php

namespace Database\Seeders;

use App\Enums\Attend;
use App\Enums\CourseType;
use App\Enums\CulturalActivity;
use App\Enums\EnumPermission;
use App\Enums\WeekDays;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DataStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('data_student')->insert(
            [
                [
                    'section' => 'انجليزي',
                    'level' => 'الاول',
                    'attend' => 'online',
                    'course_type' => 'regular',
                    'course_days' => json_encode([WeekDays::su,WeekDays::tu,WeekDays::th]),
                    'course_start_time' => Carbon::createFromFormat('H:i A','8:00 AM')->format('Y-m-d H:i:s'),
                    'course_end_time' => Carbon::createFromFormat('H:i A','10:00 AM')->format('Y-m-d H:i:s'),
                    'classroom_name' => 'القاعة الاولى',
                    'cultural_activity' => 'no',
                    'payment_method' => 'cash',
                    'signature' => 'signature.jpeg',
                    'student_id' => 1,
                ],
                [
                    'section' => 'انجليزي',
                    'level' => 'الثاني',
                    'attend' => 'physicist',
                    'course_type' => 'conversation',
                    'course_days' => json_encode([WeekDays::sa,WeekDays::mo,WeekDays::we]),
                    'course_start_time' => Carbon::createFromFormat('H:i A','1:00 PM')->format('Y-m-d H:i:s'),
                    'course_end_time' => Carbon::createFromFormat('H:i A','3:00 PM')->format('Y-m-d H:i:s'),
                    'classroom_name' => 'القاعة الثانية',
                    'cultural_activity' => 'yes',
                    'payment_method' => 'cash',
                    'signature' => 'signature.jpeg',
                    'student_id' => 2,
                ],
            ]
        );
    }
}
