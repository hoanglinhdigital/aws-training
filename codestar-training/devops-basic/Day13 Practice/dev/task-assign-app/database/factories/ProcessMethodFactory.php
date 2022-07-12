<?php

namespace Database\Factories;

use App\Models\ProcessMethod;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProcessMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProcessMethod::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $assigner = $this->faker->randomElement([1, 2, 3, 4, 5 , 6]);
        return [
            'code' => Str::random(10),
            'name' => $this->faker->userName,
            'assigner' => $assigner,
        ];
    }
}
