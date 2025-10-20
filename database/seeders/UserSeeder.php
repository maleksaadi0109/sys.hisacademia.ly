<?php

namespace Database\Seeders;

use App\Enums\EnumPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                [

                    'name' => 'عبد الرحمن',
                    'username' => 'abdulrahman',
                    'email' => 'admin@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'admin',
                    'status' => 'active',
                    'employee_number' => 'emp_num_946',
                    'permission' => json_encode([EnumPermission::modification,EnumPermission::delete,EnumPermission::view_report]),
                ],
                [

                    'name' => 'خالد',
                    'username' => 'khaled',
                    'email' => 'khaled@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'data_entry',
                    'status' => 'active',
                    'employee_number' => 'emp_num_885',
                    'permission' => json_encode([EnumPermission::modification,EnumPermission::delete]),
                ],
                [

                    'name' => 'يوسف',
                    'username' => 'yusuf',
                    'email' => 'yusuf@example.com',
                    'password' => bcrypt('1234'),
                    'user_type' => 'financial_employee',
                    'status' => 'active',
                    'employee_number' => 'emp_num_758',
                    'permission' => json_encode([EnumPermission::modification,EnumPermission::delete]),
                ],
            ]
            );
    }
}
