<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Priority;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PriorityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Priority::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $priority = $this->faker->randomElement([1, 2, 3, 4, 5 , 6]);
        return [
            'code' => Str::random(10),
            'name' => $this->faker->userName,
            'priority' => $priority,
        ];
    }
}
