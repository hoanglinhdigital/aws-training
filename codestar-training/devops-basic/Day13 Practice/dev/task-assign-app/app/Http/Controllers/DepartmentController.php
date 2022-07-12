<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {        
        $departmentList = Department::with('staffs')->get();
        $positionList = Staff::get()->groupBy('position')->all();


        $reformatedDepartmentList = $this->reformatDepartmentList($departmentList, 'name', 'staffs');
        $reformatedPositionList = $this->reformatPositionList($positionList);


        $res = [
            [
                'text' => 'Chức vụ',
                'children' => $reformatedPositionList
            ],
            [
                'text' => 'Phòng ban',
                'children' => $reformatedDepartmentList
            ]
        ];



        return response()->json($res, Response::HTTP_OK);
    }

    private function reformat($collection, $textAttr, $childAttr, $stop = false)
    {
        $reformat = [];
        if ($collection == null) {
            return null;
        }

        foreach ($collection as $item) {
            $reformatObj = [
                'text' => $item->$textAttr,
            ];

            if (!$stop && $item->$childAttr !== null && count($item->$childAttr) > 0) {
                $reformatObj['children'] = $this->reformat($item->$childAttr, $textAttr, $childAttr, $stop);
            }

            $reformat[] = $reformatObj;
        }

        return $reformat;
    }


    private function reformatDepartmentList($departmentList) {
        $reformat = [];

        foreach ($departmentList as $department) {

            $reformatObj = [
                'text' => $department->name
            ];

            $staffs = $department->staffs;

            $children = [];
            foreach ($staffs as $staff) {
                $children[] = [
                    'text' => $staff->name, 
                    'id' => $staff->id
                ];
            }

            $reformatObj['children'] = $children;

            $reformat[] = $reformatObj;
        }

        return $reformat;
    }


    private function reformatPositionList($positionList) {
        $reformat = [];

        foreach ($positionList as $position => $staffs) {
            $reformatObj = [
                'text' => $position,
            ];

            $children = [];
            foreach ($staffs as $staff) {
                $children[] = [
                    'text' => $staff->name,
                    'id' => $staff->id,
                ];
            }

            $reformatObj['children'] = $children;

            $reformat[] = $reformatObj;

        }

        return $reformat;
    }
}
