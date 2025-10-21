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

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'id' => 4,
                    'name' => 'وليد',
                    'username' => 'walid',
                    'email' => 'walid@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'student',
                    'status' => 'active',
                    'employee_number' => 'stud_num_4',
                    'permission' => json_encode([EnumPermission::modification]),
                ],
                [
                    'id' => 5,
                    'name' => 'فادي',
                    'username' => 'fadi',
                    'email' => 'fadi@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'student',
                    'status' => 'active',
                    'employee_number' => 'stud_num_5',
                    'permission' => json_encode([EnumPermission::modification]),
                ],
            ]
        );

        DB::table('students')->insert(
            [
                [
                    'id' => 1,
                    'en_name' => 'walid',
                    'id_number' => '123456789',
                    'nationality' => 'ليبي',
                    'date_of_birth' => Carbon::parse('1998-09-01'),
                    'academic_qualification' => 'إجازة جامعية فنون',
                    'phone' => '123456789',
                    'pass_image' => 'pass.jpg',
                    'user_id' => 4,
                ],
                [
                    'id' => 2,
                    'en_name' => 'fadi',
                    'id_number' => '123456789',
                    'nationality' => 'عراقي',
                    'date_of_birth' => Carbon::parse('1997-07-04'),
                    'academic_qualification' => 'إجازة جامعية ادارة',
                    'phone' => '123456789',
                    'pass_image' => 'pass.jpg',
                    'user_id' => 5,
                ],
            ]
        );

        DB::table('user_course')->insert(
            [
                [
                    'user_id' => 4,
                    'course_id' => 1,
                ],
                [
                    'user_id' => 5,
                    'course_id' => 2,
                ],
            ]
            );
    }
}
