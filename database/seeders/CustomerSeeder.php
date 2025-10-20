<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customer')->insert(
            [
                [
                    'id' => '1',
                    'name' => 'بلال الطاهر',
                    'phone' => '123456789',
                    'address' => 'طرابلس',
                ],
                [
                    'id' => '2',
                    'name' => 'نزار مراد',
                    'phone' => '123456789',
                    'address' => 'بنغازي',
                ]
            ]
        );
    }
}
