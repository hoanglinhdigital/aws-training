<?php

namespace Database\Factories;

use App\Models\TimeSheet;
use App\Models\JobAssign;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobAssignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = JobAssign::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'job_id' => 0,
                'staff_id' => 0,
                'process_method_id' => 0,
                'parent_id' => NULL,
                'direct_report' => 1,
                'sms' => 'Check sms',
                'status' => 'active',
                'deny_reason' => $this->faker->jobTitle,
        ];
    }
//
//    public function configure()
//    {
//        return $this->afterCreating(function (TimeSheet $timeSheet) {
//            TimeSheet::factory()->count(5)->create(
//                ['job_assign_id' => $timeSheet->id]
//            );
//        });
//    }

}
