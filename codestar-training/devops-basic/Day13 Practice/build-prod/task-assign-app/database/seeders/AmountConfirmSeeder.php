<?php

namespace Database\Seeders;

use App\Models\AmountConfirm;
use App\Models\Job;
use App\Models\JobAssign;
use Illuminate\Database\Seeder;

class AmountConfirmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $jobAssign = JobAssign::with('job')->get()->random();
        
        AmountConfirm::factory()->count(5)->create([
            'job_assign_id' => $jobAssign->id,
            'staff_id' => $jobAssign->job->assigner_id
        ]);
        


    }
}
