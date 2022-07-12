<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Exception;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    const DEFAULT_PAGINATE = 10;

    public function index()
    {
        $staff = Staff::with([
            'info:id,staff_id,date_of_birth,phone',
            'department',
        ])
        ->orderBy('name')
        ->whereHas('account', function($query) {
            $query->where('is_admin', false);
        })->paginate($this::DEFAULT_PAGINATE);
        

        foreach ($staff as $sta) {
            $sta->dob = $sta->info ? $sta->info->date_of_birth : null;
            $sta->phone = $sta->info ? $sta->info->phone : null;
            $sta->department = $sta->department ? $sta->department->name : null; 
        }





        $departments = Department::all();

        return view('staff-list', compact(['staff', 'departments']));
    }


    public function update(Request $request, $id) {

        $staff = Staff::find($id);
        if (!$staff) {
            return back()->with('error', 'Không tồn tại nhân viên');
        }



        $data = $request->only(['department_id', 'position']);

        try {
            $staff->update($data);
            return back()->with('success', 'Cập nhật thông tin thành công');
        }
        catch(Exception $e) {
            return back()->with('error', 'Đã có lỗi xảy ra');
        }


    }
}
