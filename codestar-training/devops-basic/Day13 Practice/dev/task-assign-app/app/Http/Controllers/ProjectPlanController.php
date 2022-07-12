<?php

namespace App\Http\Controllers;
use App\Models\JobAssign;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectPlanController extends Controller
{
    public const DEFAULT_PAGINATE = 4;


    public function list(){
        $staffId = Auth::user()->staff_id;
        $projects = Project::belongsToStaff($staffId)
            ->orderBy('name', 'ASC')
            ->get();
        $jobs = [];

        if ($projects->count() > 0) {
            $firstProject = $projects[0];
            $jobs = $firstProject->jobs()->paginate(self::DEFAULT_PAGINATE);
            $jobs = $this->reformat($jobs);
        }
        
        return view('site.project-plan.project-plan', compact('jobs','projects'));

    }

    public function queryJobs(Request $request, $id)
    {
        $staffId = Auth::user()->staff_id;
        $projects = Project::belongsToStaff($staffId)
        ->with('jobs')
        ->orderBy('name', 'ASC')
        ->get();

        $curProject = $projects->filter(fn ($project) => $project->id == $id)->first();
        if (!$curProject) {
            return redirect()->back()->with('error', 'Không tìm thấy dự án');
        }        

        $jobs = $curProject->jobs()->paginate(self::DEFAULT_PAGINATE);
        $jobs = $this->reformat($jobs);
        
        return view('site.project-plan.project-plan', compact('jobs','curProject', 'projects'));
    }

    public function search(Request $request){
        $projectId = $request->input('project_id');
        return redirect()->route('project-plan.queryJobs', ['id' => $projectId]);
       
    }

    private function reformat($jobs)
    {
        foreach ($jobs as $job) {
            $job->assignees = $job->getAssignees(true);
            $mainAssignee = $job->getMainAssignee();
            $job->main_assignee_phone = $mainAssignee ? $mainAssignee->info->phone : null;
            $job->main_assignee_email = $mainAssignee ? $mainAssignee->info->email : null;
            $job->timesheet_amount = $job->calculateTimeSheetAmount();
            $job->finished_percent = $job->getFinishedPercent();   
        }
        return $jobs;
    }
}
