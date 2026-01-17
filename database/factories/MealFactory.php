<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class MealFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->title ,
            'description' => $this->faker->sentence ,
            'price' => $this->faker->numberBetween(1,3) ,
            'compare_price' => $this->faker->numberBetween(1,3) ,
            'image' => $this->faker->imageUrl ,
            'status' => 'active' ,
            'preparation_time' => $this->faker->numberBetween(1,3) ,
            'category_id' => $this->faker->numberBetween(1,10) ,
        ];
    }
}
