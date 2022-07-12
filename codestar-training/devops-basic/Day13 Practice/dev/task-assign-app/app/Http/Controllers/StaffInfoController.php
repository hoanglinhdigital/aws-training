<?php

namespace App\Http\Controllers;

use App\Models\StaffInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffInfoController extends Controller
{

    
    public function show($id) 
    {   
        $staffInfo = StaffInfo::where('staff_id', $id)->with('staff:id,name')->first();
        
        if (!$staffInfo)
            return back()->with('error', 'Không tồn tại nhân viên');
        
        $staffInfo->name = $staffInfo->staff->name;
        return view('staff-info', compact('staffInfo'));
    }



    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|regex:/^[\pL\s]+$/u|max:80',
            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|size:10'
        ];

        $messages = [
            'name.required' => 'Trường tên là bắt buộc',
            'name.regex' => 'Tên không được phép chứa các ký tự không phải chữ cái',
            'name.max' => 'Độ dài tên không vượt quá 80 ký tự',
            'phone.regex' => 'Định dạng số điện thoại không hợp lệ',
            'phone.size' => 'Số điện thoại phải bao gồm 10 chữ số'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $staffInfo = StaffInfo::where('staff_id', $id)->with('staff')->first();
        
        try {
            $staffInfoUpdates = [
                'date_of_birth' => $request->has('dob') ? $request->dob : null,
                'gender' => $request->has('gender') ? $request->gender : null,
                'address' => $request->has('address') ? $request->address : null,
                'phone' => $request->has('phone') ? $request->phone : null,
            ];
            $staffInfo->update($staffInfoUpdates);
            
            $staffInfo->staff->update(['name' => $request->name]);
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
        } 
        catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra!');
        }


        

    }
}
