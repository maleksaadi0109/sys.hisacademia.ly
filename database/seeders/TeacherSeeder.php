<?php

namespace Database\Seeders;

use App\Enums\EnumPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [
                    'id' => '6',
                    'name' => 'محمد',
                    'username' => 'mohammed',
                    'email' => 'mohammed@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'teacher',
                    'status' => 'active',
                    'employee_number' => 'teach_num_6',
                    'permission' => json_encode([EnumPermission::modification,EnumPermission::delete]),
                ],
                [
                    'id' => '7',
                    'name' => 'ياسر',
                    'username' => 'yaser',
                    'email' => 'yaser@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'teacher',
                    'status' => 'active',
                    'employee_number' => 'teach_num_7',
                    'permission' => json_encode([EnumPermission::modification,EnumPermission::delete]),
                ],
            ]
        );

        DB::table('teachers')->insert(
            [
                [
                    'en_name' => 'mohammed',
                    'id_number' => '123456789',
                    'nationality' => 'مصري',
                    'date_of_birth' => Carbon::parse('1990-09-01'),
                    'academic_qualification' => 'إجازة جامعية لغة انجليزية',
                    'phone' => '123456789',
                    'pass_image' => 'pass.jpg',
                    'user_id' => '6',
                ],
                [
                    'en_name' => 'yaser',
                    'id_number' => '123456789',
                    'nationality' => 'اردني',
                    'date_of_birth' => Carbon::parse('1989-07-04'),
                    'academic_qualification' => 'ماستر ادب انجليزي',
                    'phone' => '123456789',
                    'pass_image' => 'pass.jpg',
                    'user_id' => '7',
                ],
            ]
        );
    }
}
