<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoworkingSpace;

class CoworkingSpaceSeeder extends Seeder
{
    public function run()
    {
        $spaces = [
            [
                'name' => 'مساحة عمل مشتركة - وسط المدينة',
                'location' => 'وسط المدينة',
                'capacity' => 5,
                'daily_price' => 25.00,
                'weekly_price' => 75.00,
                'monthly_price' => 250.00,
                'three_month_price' => 600.00,
                'student_daily_price' => 20.00,
                'student_weekly_price' => 60.00,
                'student_monthly_price' => 200.00,
                'student_three_month_price' => 480.00,
            ],
            [
                'name' => 'مساحة عمل مشتركة - المنطقة الشمالية',
                'location' => 'المنطقة الشمالية',
                'capacity' => 3,
                'daily_price' => 20.00,
                'weekly_price' => 60.00,
                'monthly_price' => 200.00,
                'three_month_price' => 500.00,
                'student_daily_price' => 15.00,
                'student_weekly_price' => 45.00,
                'student_monthly_price' => 150.00,
                'student_three_month_price' => 375.00,
            ],
            [
                'name' => 'مساحة عمل مشتركة - المنطقة الجنوبية',
                'location' => 'المنطقة الجنوبية',
                'capacity' => 4,
                'daily_price' => 22.00,
                'weekly_price' => 65.00,
                'monthly_price' => 220.00,
                'three_month_price' => 550.00,
                'student_daily_price' => 18.00,
                'student_weekly_price' => 50.00,
                'student_monthly_price' => 175.00,
                'student_three_month_price' => 440.00,
            ],
            [
                'name' => 'مساحة عمل مشتركة - المنطقة الشرقية',
                'location' => 'المنطقة الشرقية',
                'capacity' => 6,
                'daily_price' => 30.00,
                'weekly_price' => 85.00,
                'monthly_price' => 280.00,
                'three_month_price' => 700.00,
                'student_daily_price' => 25.00,
                'student_weekly_price' => 70.00,
                'student_monthly_price' => 225.00,
                'student_three_month_price' => 560.00,
            ],
            [
                'name' => 'مساحة عمل مشتركة - المنطقة الغربية',
                'location' => 'المنطقة الغربية',
                'capacity' => 2,
                'daily_price' => 18.00,
                'weekly_price' => 50.00,
                'monthly_price' => 180.00,
                'three_month_price' => 450.00,
                'student_daily_price' => 15.00,
                'student_weekly_price' => 40.00,
                'student_monthly_price' => 140.00,
                'student_three_month_price' => 360.00,
            ]
        ];

        foreach ($spaces as $spaceData) {
            CoworkingSpace::updateOrCreate(
                ['name' => $spaceData['name']],
                $spaceData
            );
        }
    }
}

