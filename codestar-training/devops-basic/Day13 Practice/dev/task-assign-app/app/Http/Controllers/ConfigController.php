<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function list(){
        $configurations = Configuration::getSystemConfiguration();
        return view('site.config.configuration', compact('configurations'));
    }
    
    public function update(Request $request){

        $data = $request->only([
            'period',
            'job_code',
            'production_volume',
            'volume_interface',
            'get_job',
            'implementation_plan',
        ]);

        foreach ($data as $field => $value) {
            Configuration::set($field, ['value' => $value]);
        }
      
        return redirect()->route('config.list')->with('success','Cập nhật cấu hình thành công');
    }

}