<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobAssign;
use App\Models\TimeSheet;

class TimeSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listJobAssign = JobAssign::take(10)->get()->random();
        TimeSheet::factory()->count(10)->create(
            [
                'job_assign_id' => $listJobAssign->id,
            ]
        );
    }
}
