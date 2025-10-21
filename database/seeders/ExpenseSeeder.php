<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expenses')->insert(
            [
                [
                    'bill_number' => 'test-9876',
                    'value' => 30,
                    'currency' => 'USD',
                    'date' => Carbon::parse('2024-09-10'),
                    'context' => 'مصاريف كهرباء',
                    'section' => 'قسم التدريبات',
                    'review_number' => '2067',
                ],
                [
                    'bill_number' => 'test-9877',
                    'value' => 20,
                    'currency' => 'USD',
                    'date' => Carbon::parse('2024-09-8'),
                    'context' => 'مصاريف مياه',
                    'section' => 'قسم الادارة',
                    'review_number' => '2068',
                ],
            ]
            );
    }
}
