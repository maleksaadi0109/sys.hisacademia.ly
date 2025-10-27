<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class coworkingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'مساحة عمل ' . $this->faker->unique()->word, // Unique word for name
            'location' => $this->faker->streetAddress, // Fake street address
            'daily_price' => $this->faker->randomFloat(2, 15, 30), // Random price between 15.00 and 30.00
            'weekly_price' => $this->faker->randomFloat(2, 80, 150),
            'monthly_price' => $this->faker->randomFloat(2, 300, 500),
            'three_month_price' => $this->faker->randomFloat(2, 800, 1300),
            'is_open_weekdays' => $this->faker->boolean(90), // 90% chance of being true
            'open_time' => $this->faker->randomElement(['08:00:00', '09:00:00', '10:00:00']),
            'close_time' => $this->faker->randomElement(['20:00:00', '21:00:00', '22:00:00', '23:00:00']),
            'internet_speed' => $this->faker->randomElement(['20Mbps', '30Mbps', '50Mbps', '100Mbps']),
            'address_line1' => $this->faker->streetName, // Using streetName for simplicity
            // Add other fields from your migration as needed
        ];
    }
}
