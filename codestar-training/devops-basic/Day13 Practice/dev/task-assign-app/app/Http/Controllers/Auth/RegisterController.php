<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Account;
use App\Models\Department;
use App\Models\Staff;
use App\Models\StaffInfo;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }


    public function store(RegisterRequest $request)
    {
        // TODO: Default Department
        $fakeDepartment = Department::first();

        $newStaff = Staff::create(['name' => $request->name, 'department_id' => $fakeDepartment->id, 'position' => '?']);

        StaffInfo::create([
            'date_of_birth' => $request->has('dob') ? $request->dob : null,
            'gender' => $request->has('gender-option') ? $request->gender : null,
            'address' => $request->has('address') ? $request->address : null,
            'phone' => $request->has('phone') ? $request->phone : null,
            'staff_id' => $newStaff->id,
        ]);


        Account::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'staff_id' => $newStaff->id,
        ]);



        return redirect()->route('login')->with('success', 'Tạo tài khoản thành công');
    }
}
