<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\StaffInfo;
use Illuminate\Database\Seeder;

class AccountStaffInfoSeeder extends Seeder
{

    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staffId = 3;
        Account::factory()->create(['staff_id' => $staffId]);
        StaffInfo::factory()->create(['staff_id' => $staffId]);
        
    }
}
