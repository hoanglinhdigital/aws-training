<?php

namespace Database\Seeders;

use App\Models\JobType;
use App\Models\Priority;

use Illuminate\Database\Seeder;

class PriorityJobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $listPriority = Priority::factory()->count(20)->create();
        $listJobType = JobType::factory()->count(20)->create();
    }
}
