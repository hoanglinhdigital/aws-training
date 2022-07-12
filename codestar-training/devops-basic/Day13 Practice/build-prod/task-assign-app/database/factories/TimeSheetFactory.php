<?php

namespace Database\Factories;

use App\Models\TimeSheet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeSheetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeSheet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_assign_id' => 0,
            'from_date' => Carbon::now(),
            'to_date' => Carbon::tomorrow(),
            'from_time' => Carbon::now(),
            'to_time' => Carbon::now(),
            'content' => $this->faker->sentence,
        ];
    }
}
