<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $fillable = ['field', 'value', 'available_values', 'note'];

    const CONFIG_JOB_CODE = 'job_code';
    const CONFIG_JOB_ACCEPT = 'get_job';
    const CONFIG_PERIOD = 'period';
    const CONFIG_WORKPLAN = 'implementation_plan';
    const CONFIG_PRODUCTION_AMOUNT = 'production_volume';
    const CONFIG_ASSIGN_AMOUNT = 'volume_interface';

    const DEFAULT_VALUE = [
        Configuration::CONFIG_JOB_CODE => [
            'field' => Configuration::CONFIG_JOB_CODE,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Mã công việc'
        ],
        Configuration::CONFIG_JOB_ACCEPT => [
            'field' => Configuration::CONFIG_JOB_ACCEPT,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Nhận việc'
        ],
        Configuration::CONFIG_PERIOD => [
            'field' => Configuration::CONFIG_PERIOD,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Kỳ'
        ],
        Configuration::CONFIG_WORKPLAN => [
            'field' => Configuration::CONFIG_WORKPLAN,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Kế hoạch thực hiện'
        ],
        Configuration::CONFIG_PRODUCTION_AMOUNT => [
            'field' => Configuration::CONFIG_PRODUCTION_AMOUNT,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Khối lượng sản xuất'
        ],
        Configuration::CONFIG_ASSIGN_AMOUNT => [
            'field' => Configuration::CONFIG_ASSIGN_AMOUNT,
            'value' => 0,
            'available_values' => '0, 1',
            'note' => 'Khối lượng giao'
        ],

    ];
    
    public static function get($fieldName)
    {
        $config = Configuration::where('field', $fieldName)->first();
        if (!$config) {
            $savedConfig = Configuration::set($fieldName, Configuration::DEFAULT_VALUE[$fieldName]);
            return $savedConfig;
        }
        return $config;
    }

    public static function set($fieldName, $value)
    {
        $config = Configuration::where('field', $fieldName)->first();
        
        if ($config) {
            $config->update($value);
            $config->refresh();
        }
        else {
            $config = Configuration::create($value);
        }

        return $config;
    }

    public static function gets($fieldNames)
    {
        $configurations = [];
        
        $configurationsInDB = Configuration::whereIn('field', $fieldNames)->get();

        foreach ($fieldNames as $fieldName) {
            $config = $configurationsInDB->where('field', $fieldName)->first();
            
            if (!$config) {
                $configurations[] = Configuration::set($fieldName, Configuration::DEFAULT_VALUE[$fieldName]);
            }
            else{
                $configurations[] = $config;
            }
        }
        
        return $configurations;

    }

    public static function getSystemConfiguration()
    {
        $fieldNames = [
            Configuration::CONFIG_JOB_CODE,
            Configuration::CONFIG_JOB_ACCEPT,
            Configuration::CONFIG_PERIOD, 
            Configuration::CONFIG_WORKPLAN,
            Configuration::CONFIG_PRODUCTION_AMOUNT,
            Configuration::CONFIG_ASSIGN_AMOUNT,
        ];

        return Configuration::gets($fieldNames);

    }


}
