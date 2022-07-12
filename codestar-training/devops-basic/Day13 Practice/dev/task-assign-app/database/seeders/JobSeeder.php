<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Project;
use App\Models\JobType;
use App\Models\Priority;
use App\Models\Staff;

use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listPriority = Priority::take(10)->get()->random();
        $listJobType = JobType::take(10)->get()->random();
        $listProject = Project::take(10)->get()->random();
        $listStaff = Staff::take(10)->get()->random();

        Job::factory()->count(10)->create(
            [   'assigner_id' => $listPriority->id,
                'job_type_id' => $listJobType->id,
                'project_id' => $listProject->id,
                'priority_id' => $listStaff->id,
            ]
        );

    }
}
