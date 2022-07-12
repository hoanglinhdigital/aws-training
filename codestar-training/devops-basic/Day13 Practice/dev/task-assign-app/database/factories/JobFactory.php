<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobAssign;
use App\Models\JobType;
use App\Models\Priority;
use App\Models\Project;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = Job::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $workingDay = $this->faker->randomElement([2, 3, 4, 5]);
        $assigner = Staff::inRandomOrder()->first();
        JobType::factory()->count(1)->create();
        Project::factory()->count(1)->create();
        Priority::factory()->count(1)->create();
        $jobType = JobType::inRandomOrder()->first();
        $project = Project::inRandomOrder()->first();
        $prio = Priority::inRandomOrder()->first();

        return [
            'code' => Str::random(10),
            'name' => $this->faker->word(),
            'assigner_id' => $assigner->id,
            'parent_id'    => NULL,
            'job_type_id' => $jobType->id,
            'project_id' => $project->id,
            'priority_id' => $prio->id,
            'deadline' => Carbon::now()->addDays($workingDay),
            'period' => $workingDay,
            'period_unit' => Job::PERIOD_UNIT_DAY,
            'lsx_amount' =>  NULL,
            'assign_amount' => NULL,
            'description' => $this->faker->sentence(),
        ];
    }
}
