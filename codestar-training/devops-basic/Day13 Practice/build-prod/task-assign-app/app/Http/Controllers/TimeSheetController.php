<?php

namespace App\Http\Controllers;

use App\Http\Common\JobChecker;
use App\Http\Requests\TimeSheetRequest;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\Staff;
use App\Models\TimeSheet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TimeSheetController extends Controller
{
    public const DEFAULT_PAGINATE = 15;
    

    public function create(Request $request)
    {
        $staffId = Auth::user()->staff_id;

        $readonly = false;
        $assignees = [];
        $directJobs = [];
        $defaultStaffId = $request->input('staff_id');
        $defaultJobId = $request->input('job_id');
        $job = Job::with('assignees')->where('id', $defaultJobId)->first();

        if ($job && $this->allowToModify($job, $staffId)) {
            $readonly = true;
            $assignees = $job->assignees;
            
            if (!$defaultStaffId && $assignees->count() > 0) {
                $firstAssignee = $assignees[0];
                $defaultStaffId = $firstAssignee->id;
            }

            $timeSheets = $this->queryTimeSheets($job->id, $defaultStaffId);
        }
        else {
            $directJobs = $this->getDirectJobs($staffId);
            $timeSheets = $this->getTimeSheets($directJobs, $staffId);
        }

        return view('site.time-sheet.timesheet', compact('assignees', 'defaultJobId', 'defaultStaffId', 'job', 'timeSheets', 'directJobs', 'readonly'));
    }

    public function store(Request $request){

        $validator = $this->makeValidator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        
        $jobId = $request->input('job_id');
        $assigneeId = Auth::user()->staff_id;
        $jobAssign = JobAssign::where([
            'job_id' => $jobId,
            'staff_id' => $assigneeId
        ])
        ->with('job')
        ->first();
        $job = $jobAssign->job;

        $data = $request->only(['from_date', 'to_date', 'from_time', 'to_time','content']);

        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $deadline = Carbon::parse($job->deadline);

        if (JobChecker::isPastDueDeadline($fromDate, $toDate, $deadline)) {
            return redirect()->back()->withInput()->with('error', 'Ng??y nh???p kh??ng ???????c v?????t qu?? deadline');
        }

        if ($job->assign_amount && TimeSheet::isOverAssignAmount($data, $job->id, $job->assign_amount)) {
            return redirect()->back()->withInput()->with('error', '% ho??n th??nh v?????t qu?? kh???i l?????ng c??ng vi???c');
        }

        $data = array_merge(['job_assign_id' => $jobAssign->id], $data);
        try {
            TimeSheet::create($data);
            return redirect()->route('timesheet.create', ['job_id' => $jobId])->with('success','???? th??m timesheet th??nh c??ng');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', '???? c?? l???i x???y ra');
        }   


    }

    public function edit($id){
        $timeSheet = TimeSheet::with([
            'jobAssign',
            'jobAssign.job',
            'jobAssign.job.assignees'
        ])
        ->where('id', $id)
        ->first();

        if (!$timeSheet) {
            return redirect()->back()->with('error', 'Kh??ng t??m th???y timesheet');
        }

        $readonly = false;
        
        $job = $timeSheet->jobAssign->job;
        $timeSheet->percentage_completed = $timeSheet->getPercentageCompleted();

        $staffId = Auth::user()->staff_id;
        
        if ($this->allowToModify($job, $staffId)) {
            $readonly = true;
            $assigneeId = $timeSheet->jobAssign->staff_id;
            $timeSheets = $this->queryTimeSheets($job->id, $assigneeId);
            $assignees = $job->assignees;
            return view('site.time-sheet.timesheet-edit', compact('assignees', 'job', 'timeSheets', 'timeSheet', 'readonly'));
        }

        $timeSheets = TimeSheet::belongsToJobAssign($job->id, $staffId)
        ->orderBy('created_at', 'DESC')
        ->get();

        $directJobs = $this->getDirectJobs($staffId);
        return view('site.time-sheet.timesheet-edit', compact('job', 'directJobs', 'timeSheets', 'timeSheet', 'readonly'));

    }

    public function update(Request $request, $id){
        $action = $request->input('action');
        if ($action == 'reset') {
            return redirect()->route('timesheet.create');
        }

        $timeSheet = TimeSheet::with([
            'jobAssign',
            'jobAssign.job'
        ])
        ->where('id', $id)
        ->first();

        if (!$timeSheet) {
            return redirect()->back()->withInput()->with('error', 'Kh??ng t??m th???y timesheet');
        }

        $data = $request->only(['from_date', 'to_date', 'from_time', 'to_time','content']);

        $validator = $this->makeValidator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $job = $timeSheet->jobAssign->job;
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $deadline = Carbon::parse($job->deadline);
        
        if (JobChecker::isPastDueDeadline($fromDate, $toDate, $deadline)) {
            return redirect()->back()->withInput()->with('error', 'Ng??y nh???p kh??ng ???????c v?????t qu?? deadline');
        }

        if ($job->assign_amount && TimeSheet::isOverAssignAmount($data, $job->id, $job->assign_amount, $timeSheet->id)) {
            return redirect()->back()->withInput()->with('error', '% ho??n th??nh v?????t qu?? kh???i l?????ng c??ng vi???c');
        }

        try {
            $timeSheet->update($data);
            return redirect()->route('timesheet.edit', ['id' => $id])->with('success','???? s???a timesheet th??nh c??ng');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', '???? c?? l???i x???y ra');
        }   
    }

    public function destroy($id) {
        $timeSheet = TimeSheet::find($id);
        if (!$timeSheet) {
            return redirect()->back()->withInput()->with('error', 'Kh??ng t??m th???y timesheet');
        }

        try {
            $timeSheet->delete();
            return redirect()->route('timesheet.create')->with('success','???? x??a timesheet th??nh c??ng');

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', '???? c?? l???i x???y ra');
        } 
    }

    private function makeValidator($data)
    {
        $rules = [
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'from_time' => ['required', 'date_format:H:i'],
            'to_time' => ['required', 'date_format:H:i'], 
            'content' => 'required',
        ];

        $messages = [
            'from_date.required' => 'Ng??y b???t ?????u kh??ng ???????c ????? tr???ng!',
            'from_date.date' => 'Ng??y b???t ?????u kh??ng h???p l???!',
            'to_date.after_or_equal' => 'Ng??y k???t th??c kh??ng ???????c v?????t qu?? ng??y b???t ?????u!',
            'to_date.required' => 'Ng??y k???t th??c kh??ng ???????c ????? tr???ng!',
            'to_date.date' => 'Ng??y k???t th??c kh??ng h???p l???!',
            'from_time.required' => 'Th???i gian ng??y b???t ?????u kh??ng ???????c ????? tr???ng!',
            'to_time.required' => 'Th???i gian ng??y k???t th??c kh??ng ???????c tr???ng!',
            'to_time.after_or_equal' => 'Gi??? k???t th??c kh??ng ???????c v?????t qu?? gi??? b???t ?????u!',
            'content.required' => 'N???i dung kh??ng ???????c ????? tr???ng!',
        ];

        $validator = Validator::make($data, $rules, $messages);
        $validator->sometimes('to_time', 'after_or_equal:from_time', function($input) {
            return $input->from_date == $input->to_date;
        });
        
        return $validator;
    }

    private function allowToModify($job, $staffId)
    {
        $jobAssign = JobAssign::where([
            'job_id' => $job->id,
            'staff_id' => $staffId
        ])
        ->first();
        return $job->assigner_id == $staffId || ($jobAssign && $jobAssign->hasForwardChild());

    }

    private function getDirectJobs($staffId)
    {
        $directJobs = Job::with([
            'jobAssigns' => function ($query) use ($staffId){
                $query->directAssign($staffId);
            }, 
        ])
        ->whereHas('jobAssigns', function ($query) use ($staffId) {
            $query->directAssign($staffId);
        })->get();
        return $directJobs;
    }

    private function queryTimeSheets($jobId, $assigneeId)
    {
        if (!$assigneeId) {
            return TimeSheet::belongsToJob($jobId)->get();
        }
        $timeSheets = TimeSheet::belongsToJob($jobId)->whereHas('jobAssign', function ($query) use ($assigneeId) {
            $query->where('staff_id', $assigneeId);
        })
        ->orderBy('created_at', 'DESC')
        ->get();
        return $timeSheets;
    }
    
    private function getTimeSheets($jobs, $staffId)
    {
        $jobIds = $jobs->map(function($job) {
            return $job->id;
        });
        $timeSheets = TimeSheet::whereHas('jobAssign', function($query) use ($staffId) {
            $query->where('staff_id', $staffId);
        })
        ->whereHas('jobAssign.job', function($query) use ($jobIds) {
            $query->whereIn('id', $jobIds);
        })
        ->orderBy('created_at', 'DESC')
        ->get();

        return $timeSheets;
    }


}
