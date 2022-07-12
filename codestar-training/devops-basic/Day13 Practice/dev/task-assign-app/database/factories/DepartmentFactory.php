<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DepartmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Department::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => Str::random(10),
            'name' => join(" ", $this->faker->words(3))
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function () {
        })->afterCreating(function (Department $dep) {
            Staff::factory()->count(5)->create(
                ['department_id' => $dep->id]
            );
        });
    }
}
