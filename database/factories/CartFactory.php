<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1,2) ,
            'admin_id' => null ,
            'cookie_id' => null ,
            'meal_id' => 1 ,
            'quantity' => 2
        ];
    }
}
