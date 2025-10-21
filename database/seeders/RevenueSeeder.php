<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('revenue')->insert(
            [
                [
                    'course_id' => 1,
                    'type' => 'course',
                    'date_of_rec' => Carbon::parse('2024-09-01'),
                    'value' => 50,
                    'currency' => 'USD',
                    'user_id' => 4,
                    'value_rec' => 50,
                    'value_rem' => 0,
                ],
                [
                    'course_id' => 2,
                    'type' => 'course',
                    'date_of_rec' => Carbon::parse('2024-09-05'),
                    'value' => 70,
                    'currency' => 'USD',
                    'user_id' => 5,
                    'value_rec' => 30,
                    'value_rem' => 40,
                ],
            ]
            );
    }
}
