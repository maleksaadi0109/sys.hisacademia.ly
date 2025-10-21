<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationDelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('translation_del')->insert(
            [
                [
                    "number_of_sheets" => '6',
                    "number_of_transaction" => '1',
                    "context" => "معاملات + اختام شهادة ميلاد ",
                    "language" => "لغة اسبانية",
                    "customer_id" => 1,
                    "price" => '60',
                    'currency' => 'USD',
                    "translator_share" => '30',
                    "academy_share" => '30',
                    "received" =>  '60',
                    "remaining" => '0',
                    "payment_method" => "نقداً",
                    "date_of_receipt" => Carbon::parse('2024-10-23'),
                    "due_date" => Carbon::parse('2024-10-28'),
                    "delivery_date" => Carbon::parse('2024-10-28'),
                ],
                [
                    "number_of_sheets" => '7',
                    "number_of_transaction" => '1',
                    "context" => "خدمات الأكاديمية",
                    "language" => "لغة اسبانية",
                    "customer_id" => 2,
                    "price" => '550',
                    'currency' => 'USD',
                    "translator_share" => '225',
                    "academy_share" => '225',
                    "received" =>  '550',
                    "remaining" => '0',
                    "payment_method" => "نقداً",
                    "date_of_receipt" => Carbon::parse('2024-10-11'),
                    "due_date" => Carbon::parse('2024-10-28'),
                    "delivery_date" => null,
                ],
            ]);
    }
}
