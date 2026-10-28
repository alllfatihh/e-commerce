<?php

namespace Database\Factories;

use App\Models\DiscountCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DiscountCode>
 */
class DiscountCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('PROMO???')),
            'description' => fake()->sentence(3),
            'type' => 'percentage',
            'value' => fake()->randomElement([10, 15, 20, 25, 50]),
            'min_order_amount' => 0,
            'max_discount_amount' => null,
            'usage_limit' => fake()->optional()->numberBetween(10, 100),
            'used_count' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ];
    }
}
