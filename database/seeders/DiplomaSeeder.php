<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiplomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('diploma')->insert(
            [
                [
                    'id'=> '1',
                    'name' => 'الدبلوم الأول',
                    'number_course' => 2,
                    'price' => 120,
                    'currency' => 'USD'
                ],
                [
                    'id'=> '2',
                    'name' => 'الدبلوم الثاني',
                    'number_course'=> 3,
                    'price' => 290,
                    'currency' => 'USD',
                ]
            ]
        );
    }
}
