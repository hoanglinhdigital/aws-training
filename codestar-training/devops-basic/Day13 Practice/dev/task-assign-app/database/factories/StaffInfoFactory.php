<?php

namespace Database\Factories;

use App\Models\StaffInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffInfoFactory extends Factory
{
    const STAFF_ID = 3;
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaffInfo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber
        ];
    }
}
