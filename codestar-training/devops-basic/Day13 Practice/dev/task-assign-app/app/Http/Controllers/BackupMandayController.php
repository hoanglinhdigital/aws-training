<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Job;
use App\Models\Project;
use App\Http\Requests\BackupMandayRequest;

class BackupMandayController extends Controller
{
    public const DEFAULT_PAGINATE = 15;

    private function formatJobs($jobs){
        
        foreach ($jobs as $job){
            $job->code = $job->project ? $job->project->code : '?';
            $job->name_project = $job->project ? $job->project->name : '?';
            $job->total_manday_lsx = $job->lsx_amount ?? '?';
            $job->total_manday_assign = $job->assign_amount ?? '?';
            $job->total_manday_reserve = ($job->lsx_amount && $job->assign_amount) ? $job->lsx_amount - $job->assign_amount : '?';
        }
    }

    public function list(){
        $departments = Department::orderBy('name', 'ASC')->get();
        $projects = Project::orderBy('name', 'ASC')->get();
        $jobs = Job::with('project')->orderBy('name', 'ASC')->paginate(self::DEFAULT_PAGINATE);
        $this->formatJobs($jobs);
        return view('site.backup-manday.backup-manday', compact('departments', 'projects', 'jobs'));
    }

    public function search(BackupMandayRequest $request){
        $departments = Department::orderBy('name', 'ASC')->get();
        $projects = Project::orderBy('name', 'ASC')->get();
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $jobs = Job::with(['project', 'jobAssigns.assignee.department'])
            ->whereHas('project', function ($q) use($request) {
                $q->where('id', $request->project);
            })
            ->whereHas('jobAssigns.assignee.department', function ($q) use($request) {
                $q->where('id', $request->department);
            })
            ->where('deadline', '>=', $fromDate)
            ->Where('created_at', '<=', $toDate)
            ->orderBy('name', 'ASC')->paginate(self::DEFAULT_PAGINATE);
        $this->formatJobs($jobs);
        return view('site.backup-manday.backup-manday-search', compact('departments', 'projects', 'jobs'));
    }

}
