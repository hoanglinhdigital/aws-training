<?php

namespace Database\Seeders;

use App\Models\JobAssign;

use App\Models\Job;
use App\Models\Staff;
use App\Models\ProcessMethod;

use Illuminate\Database\Seeder;

class JobAssignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listJob = Job::take(10)->get()->random();
        $listStaff = Staff::take(10)->get()->random();
        $listProcessMethod = ProcessMethod::take(10)->get()->random();

        JobAssign::factory()->count(10)->create(
            [   'job_id' => $listJob->id,
                'staff_id' => $listStaff->id,
                'process_method_id' => $listProcessMethod->id,
            ]
        );
    }
}
