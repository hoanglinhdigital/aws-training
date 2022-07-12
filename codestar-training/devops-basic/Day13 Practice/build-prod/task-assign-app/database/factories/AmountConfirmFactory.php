<?php

namespace Database\Factories;

use App\Models\AmountConfirm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmountConfirmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AmountConfirm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_assign_id' => 0,
            'staff_id' => 0,
            'month' => Carbon::now(),
            'confirm_amount' => $this->faker->numberBetween(0, 100),
            'request_amount' => $this->faker->numberBetween(0, 100),
            'percentage_on' => $this->faker->numberBetween(0, 100),
            'note' => $this->faker->sentence,
        ];
    }
}
