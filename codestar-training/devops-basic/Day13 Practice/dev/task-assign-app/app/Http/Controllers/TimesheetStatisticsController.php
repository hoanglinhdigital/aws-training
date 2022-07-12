<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\Staff;
use App\Models\TimeSheet;
use Illuminate\Http\Request;

class TimesheetStatisticsController extends Controller
{
    public const DEFAULT_PAGINATE = 15;

    private function formatField($timeStatistics){
        foreach ($timeStatistics as $timeStatistic) {
            $timeStatistic->job_name =  $timeStatistic->jobAssign->job->name;
            $timeStatistic->staff_name = $timeStatistic->jobAssign->assignee->name;
            $timeStatistic->finish = $timeStatistic->timeDate();
        }
    }

    public function list(){
        $departments = Department::orderBy('id', 'DESC')->get();
        $staffs = Staff::orderBy('id', 'DESC')->get();
        $timeStatistics = TimeSheet::with('jobAssign.job')
            ->with('jobAssign.assignee')
            ->orderBy('id', 'desc')->paginate(self::DEFAULT_PAGINATE);
        $this->formatField($timeStatistics);
        return view('site.timesheet-statistics.timesheet-statistics',
            compact('timeStatistics', 'departments', 'staffs'));
    }

    public function search(Request $request){
        $jobs = Job::orderBy('id', 'DESC')->get();
        $departments = Department::orderBy('id', 'DESC')->get();
        $staffs = Staff::orderBy('id', 'DESC')->get();

        $query = TimeSheet::with('jobAssign.job')->with('jobAssign.assignee');
        if (isset($request->department) && isset($request->object_handling)) {
            $query = $query->whereHas('jobAssign.assignee', function ($q) use($request) {
                $q->where('department_id', $request->department)->where('id', $request->object_handling);
            });
        }
        if( isset($request->from_date) && isset($request->to_date) ) {
            $query = $query->whereBetween('from_date', array($request->from_date, $request->to_date));
        }
        $timeStatistics = $query->orderBy('id', 'desc')->get();
        $this->formatField($timeStatistics);
        return view('site.timesheet-statistics.timesheet-statistics-search',
            compact('timeStatistics', 'jobs', 'departments', 'staffs'));
    }
}
