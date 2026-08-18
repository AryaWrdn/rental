<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    public function definition(): array
    {
        $types = ['CITY CAR', 'SUV CAR', 'MPV', 'SEDAN'];
        $capacities = ['2x Penumpang', '4x Penumpang', '5x Penumpang', '6-7x Penumpang'];
        $transmissions = ['LEPAS KUNCI (Manual)', 'LEPAS KUNCI (Matic)', 'TANPA LEPAS KUNCI (Manual)'];

        return [
            'name' => fake()->randomElement(['Toyota Avanza', 'Honda Brio', 'Mitsubishi Pajero', 'Toyota Innova Reborn', 'Honda HR-V', 'Suzuki Ertiga', 'Daihatsu Xenia', 'Hyundai Creta']),
            'icon' => null, // Biarkan null agar di React muncul emoji 🚗
            'price' => fake()->randomElement([300000, 350000, 400000, 500000, 750000]),
            'type' => fake()->randomElement($types),
            'capacity' => fake()->randomElement($capacities),
            'transmission' => fake()->randomElement($transmissions),
            'monthly_price' => fake()->randomElement(['6 JUTA/Bulan', '7.5 JUTA/Bulan', '10 JUTA/Bulan']),
            'driver_price' => fake()->randomElement([500000, 600000, 850000, 1000000]),
        ];
    }
}