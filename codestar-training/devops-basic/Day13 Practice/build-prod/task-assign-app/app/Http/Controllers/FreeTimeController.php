<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\Skill;
use App\Models\Staff;
use App\Models\WorkPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

const HOUR = 8;

class FreeTimeController extends Controller
{
    const DEFAULT_PAGINATE = 5;
    const TYPE_WORK_PLAN = '0';
    const TYPE_FREE_TIME = '1';

    public function list(Request $request){
        $staffs = Staff::orderBy('name', 'ASC')->get();

        $queryParams = $request->only(['type', 'from_date', 'to_date', 'free_time', 'staff_id']);

        $staffCondition = isset($queryParams['staff_id']) ? ['id' => $queryParams['staff_id']] : [];

        $assignees = Staff::with([
            'jobAssigns' => fn ($query) => $query->where('status', JobAssign::STATUS_ACTIVE),
            'jobAssigns.workPlans' => fn ($query) => $query->betweenDate($queryParams['from_date'] ?? null, $queryParams['to_date'] ?? null),
            'department'
        ])
        ->where($staffCondition)
        ->orderBy('name', 'ASC')
        ->paginate(self::DEFAULT_PAGINATE)
        ->appends($queryParams);

        $assignees = $this->reformat($assignees, $queryParams['type'] ?? self::TYPE_WORK_PLAN, $queryParams['from_date'] ?? null, $queryParams['to_date'] ?? null);

        if (isset($queryParams['free_time'])) {
            $assigneeCollection = $assignees->getCollection();
            $filteredAssignees = $assigneeCollection->filter(fn ($assignee) => $assignee->total_mandays >= $queryParams['free_time']);
            $assignees->setCollection($filteredAssignees);
        }

        if (isset($queryParams['staff_id'])) {
            $request->session()->flash('hidden_staff_id', $queryParams['staff_id']);
        }
        else {
            $request->session()->put('hidden_staff_id', null);
        }
        $request->flash();

        return view('site.free-time.free-time', compact('assignees', 'staffs'));
    }

    public function search(Request $request){
        $validator = $this->makeSearchValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $queryParams = $request->only(['type', 'from_date', 'to_date', 'free_time', 'staff_id']);
        return redirect()->route('free-time.list', $queryParams);

    }

    private function reformat($assignees, $type = self::TYPE_WORK_PLAN, $fromDate = null, $toDate = null)
    {
        foreach ($assignees as $assignee) {
            
            $totalMandays = 0;
            $totalHours = 0;
            $jobAssigns = $assignee->jobAssigns;

            foreach ($jobAssigns as $jobAssign) {
                $totalMandays += $jobAssign->calculateTotalWorkAmount('manday');
                $totalHours += $jobAssign->calculateTotalWorkAmount('hour');
            }
            $assignee->working_hours = $totalHours;

            if ($type == self::TYPE_WORK_PLAN) {
                $assignee->total_mandays = $totalMandays;
                $assignee->total_hours = $totalMandays * 8;
            }
            else {
                $fromDate = Carbon::parse($fromDate);
                $toDate = Carbon::parse($toDate);
                $numDays = $toDate->diffInDays($fromDate);
                $assignee->total_mandays = $numDays - $totalMandays;
                $assignee->total_hours = $numDays * 8 - $totalHours;
            }
            $assignee->department = $assignee->department ? $assignee->department->name : null;

        }
        return $assignees;
    }

    private function makeSearchValidator($data)
    {
        $rules = [];
        $messages = [
            'from_date.required' => 'Trường từ ngày là bắt buộc',
            'to_date.required' => 'Trường đến ngày là bắt buộc',
            'to_date.after_or_equal' => 'Trường đến ngày không được sớm hơn trường từ ngày'
        ];

        $validator = Validator::make($data, $rules, $messages);
        $validator->sometimes(['from_date', 'to_date'], 'required', fn($input) => $input->type == self::TYPE_FREE_TIME);
        $validator->sometimes('to_date', 'after_or_equal:from_date', fn($input) => $input->to_date != null && $input->from_date != null);
        
        return $validator;
    }


}
