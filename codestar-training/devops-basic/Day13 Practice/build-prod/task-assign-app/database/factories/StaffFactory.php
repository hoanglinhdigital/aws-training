<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department_id' => 1,
            'name' => $this->faker->userName(),
            'position' => $this->faker->jobTitle()
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Staff $staff) {
            Job::factory()->count(5)->create(
                ['assigner_id' => $staff->id]
            );
        });
    }
}
