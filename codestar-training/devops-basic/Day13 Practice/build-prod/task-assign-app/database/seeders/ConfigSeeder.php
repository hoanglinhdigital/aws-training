<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configurations')->insert(
            [
                ['id' => 1, 'field' => 'period', 'value' => 1, 'available_values' => 0, 'note' => 'Kỳ'],
                ['id' => 2, 'field' => 'job_code', 'value' => 1, 'available_values' => 0, 'note' => 'Mã công việc'],
                ['id' => 3, 'field' => 'production_volume', 'value' => 0, 'available_values' => 0, 'note' => 'Khối lượng LSX'],
                ['id' => 4, 'field' => 'volume_interface', 'value' => 1, 'available_values' => 0, 'note' => 'Khối lượng giao'],
                ['id' => 5, 'field' => 'get_job', 'value' => 1, 'available_values' => 0, 'note' => 'Nhận việc'],
                ['id' => 6, 'field' => 'implementation_plan', 'value' => 1, 'available_values' => 0, 'note' => 'Kế hoạch thực hiện'],
            ]
        );
    }
}