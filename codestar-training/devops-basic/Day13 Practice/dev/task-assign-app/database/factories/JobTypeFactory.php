<?php

namespace Database\Factories;

use App\Models\JobType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $workingDay = $this->faker->randomElement([2, 3, 4, 5]);
        $common = $this->faker->randomElement([0, 1]);
        return [
            'code' => Str::random(10),
            'name' => $this->faker->userName,
            'deadline' => $workingDay,
            'common' => $common,
        ];
    }

}
