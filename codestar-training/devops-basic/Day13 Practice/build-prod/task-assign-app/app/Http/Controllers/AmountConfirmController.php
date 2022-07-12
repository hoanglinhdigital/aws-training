<?php

namespace App\Http\Controllers;

use App\Models\AmountConfirm;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\TimeSheet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AmountConfirmController extends Controller
{

    public function create(Request $request)
    {

        $confirmAssigneeId = Auth::user()->staff_id;

        $jobId = $request->input('job_id');


        $job = Job::with([
            'assignees',
            'jobAssigns',
            'jobAssigns.assignee',
            'jobAssigns.processMethod',
            'jobAssigns.timeSheets',
            'jobAssigns.amountConfirms' => function($query) {
                $query->orderBy('month', 'DESC');
            },
        ])
        ->where('id', $jobId)
        ->first();

        $amountConfirms = [];

        if ($job->assignees->count() > 0) {

            if ($job->assigner_id == $confirmAssigneeId) {
                [$amountConfirms, $assignees] = $this->filterAmountConfirmsAndAssignees($job);
            }
            else {
                $confirmJobAssign = $job->jobAssigns->where('staff_id', $confirmAssigneeId)->first();

                [$amountConfirms, $assignees] = $this->filterAmountConfirmsAndAssignees($job, false, $confirmJobAssign->id);
            }

            $job->assignees = $assignees;


        }

        return view('jobs.amount-confirm', compact('job', 'amountConfirms'));

    }

    public function queryAmountConfirm(Request $request) 
    {


        if (!($request->has('id') || $request->has(['staffId', 'month']))) {
            return response('', 400);
        }

        if ($request->has('id')) {
            $amountConfirmId = $request->input('id');

            $amountConfirm = AmountConfirm::with([
                'jobAssign',
                'jobAssign.job'
            ])
            ->where('id', $amountConfirmId)
            ->first();

            if (!$amountConfirm) {
                return response('', 404);
            }

            $jobAssign = $amountConfirm->jobAssign;

            $time = Carbon::parse($amountConfirm->month);

        }
        else {
            $staffId = $request->input('staffId');
            $time = Carbon::parse($request->input('month'));

            $jobId = $request->input('jobId');

            $jobAssign =  JobAssign::where([
                'job_id' => $jobId,
                'staff_id' => $staffId
            ])
            ->with([
                'job',
                'amountConfirms',
                'amountConfirms.jobAssign'
            ])
            ->first();

            if (!$jobAssign) {
                return response('', 400);
            }

            $amountConfirm = $jobAssign->amountConfirms->filter(function($amountConfirm) use ($time) {
                $confirmTime = Carbon::parse($amountConfirm->month);
                return $time->month == $confirmTime->month && $time->year == $confirmTime->year;
            })->first();

        }

        [$oldAmountConfirm, $oldAmountConfirmPercent, $requestAmount, $requestAmountPercent] = $this->calculateAmountAndPercent($amountConfirm, $jobAssign, $time);



        if (!$amountConfirm) {
            return response()->json([
                'old_confirm_amount' => $oldAmountConfirm,
                'old_confirm_percentage' => $oldAmountConfirmPercent,
                'request_amount' => $requestAmount,
                'request_percentage' => $requestAmountPercent
            ]);
        }

        $amountConfirm->old_confirm_amount = $oldAmountConfirm;
        $amountConfirm->old_confirm_percentage = $oldAmountConfirmPercent;
        $amountConfirm->request_amount = $requestAmount;
        $amountConfirm->request_percentage = $requestAmountPercent;

        return response()->json($amountConfirm);


    }

    


    public function action(Request $request) 
    {
        $action = $request->input('action');

        try {

            if ($action == 'save') {

                $validator = $this->makeValidator($request->all());
        
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
    
                
                $amountConfirmId = $request->input('amount_confirm_id');

                if ($amountConfirmId) {
  
                    return $this->updateAmountConfirm($amountConfirmId, $request);
    
                }

                return $this->createOrUpdateAmountConfirm($request);
    
            }

            if ($action == 'timesheet') {
                $jobId = $request->input('job_id');
                $assigneeId = $request->input('assignee');
                return redirect()->route('timesheet.create', [
                    'job_id' => $jobId,
                    'staff_id' => $assigneeId
                ]);
            }
            
            if ($action == 'reset') {
                return redirect()->route('amount-confirms.create', ['job_id' => $request->input('job_id')]);
            }

            $amountConfirmId = $request->input('amount_confirm_id');            
            $amountConfirm = AmountConfirm::find($amountConfirmId);

            if (!$amountConfirm) {
                return redirect()->back()->withInput()->with(
                    'error', 'Không tìm thấy xác nhận sản lượng'
                );
            }

            $amountConfirm->delete();
            return redirect()->back()->with('success', 'Xóa xác nhận sản lượng thành công');
        
            
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra');
        }

    }

    private function filterAmountConfirmsAndAssignees($job, $isAssigner=true, $parentJobAssignId=null) 
    {
        $assignees = [];
        $amountConfirms = [];

        if ($isAssigner) {
            $jobAssigns = $job->jobAssigns->filter(function($jobAssign) {
                return !$jobAssign->isForwardOrAdditional() || $jobAssign->isDirectReport();
            });
        }
        else {
            $jobAssigns = $job->jobAssigns->filter(function($jobAssign) use ($parentJobAssignId) {
                return $jobAssign->isChildOf($parentJobAssignId) && !$jobAssign->isDirectReport();
            });
        }

        foreach ($jobAssigns as $jobAssign) {

            $assignees[] = $jobAssign->assignee;

            foreach ($jobAssign->amountConfirms as $amountConfirm) {
                $amountConfirm->assignee_name = $jobAssign->assignee->name;
                $amountConfirms[] = $amountConfirm;
            }
        }

        return [$amountConfirms, $assignees];

    }


    private function calculateAmountAndPercent($amountConfirm, $jobAssign, $time) {  
        
        $oldAmountConfirm = null;
        $oldAmountConfirmPercent = null;
        $requestAmount = null; 
        $requestAmountPercent = null;
        
        $assignAmount = $jobAssign->job->assign_amount;
        
        if ($assignAmount) {
            $oldAmountConfirm = AmountConfirm::where(
                'job_assign_id', $jobAssign->id
            )
            ->whereDate('month', '<', $time)
            ->sum('confirm_amount');
    
            $timeSheets = TimeSheet::where('job_assign_id', $jobAssign->id)
            ->whereMonth('from_date', $time->month)
            ->whereYear('from_date', $time->year)
            ->get();
    
    
            $oldAmountConfirmPercent = $oldAmountConfirm * 100 / $assignAmount;
    
            $totalTimeSheetAmount = 0;
    
            foreach ($timeSheets as $timeSheet) {
                $totalTimeSheetAmount += $timeSheet->workAmountInManday();
            }
    
    
            $requestAmount = $amountConfirm ? $amountConfirm->request_amount : $totalTimeSheetAmount;
            $requestAmountPercent = ($oldAmountConfirm + $requestAmount) * 100 / $assignAmount;
        }

        return [$oldAmountConfirm, $oldAmountConfirmPercent, $requestAmount, $requestAmountPercent];

    }

    private function makeValidator($data) 
    {
        $rules = [
            'confirm_amount' => ['required', 'lte:request_amount'],
        ];

        $messages = [
            'confirm_amount.required' => 'Khối lượng xác nhận là bắt buộc',
            'confirm_amount.lte' => 'Khối lượng xác nhận không được vượt quá khối lượng đề nghị'
        ];

        return Validator::make($data, $rules, $messages);
    }

    private function updateAmountConfirm($id, $request)
    {
        $amountConfirm = AmountConfirm::find($id);
        
        if (!$amountConfirm) {
            return redirect()->back()->withInput()->with('error', 'Không tìm thấy xác nhận sản lượng');
        }

        $amountConfirm->update([
            'confirm_amount' => $request->input('confirm_amount'),
            'percentage_on' => $request->input('confirm_percentage'),
            'quality' => $request->input('quality'),
            'note' => $request->input('note')
        ]);

        return redirect()->back()->with('success', 'Xác nhận sản lượng thành công');
    }

    private function createOrUpdateAmountConfirm($request)
    {
        $jobId = $request->input('job_id');
        $staffId = $request->input('assignee');
        $time = Carbon::parse($request->input('month'));

        $confirmAssigneeId = Auth::user()->staff_id;

        $confirmAssigneeJobAssign = JobAssign::where([
            'job_id' => $jobId,
            'staff_id' => $confirmAssigneeId
        ])
        ->with('job')
        ->first();

        $jobAssign = JobAssign::where([
            'job_id' => $jobId,
            'staff_id' => $staffId
        ])
        ->first();



        $amountConfirm = AmountConfirm::where(
            'job_assign_id', $jobAssign->id
        )
        ->whereMonth('month', $time->month)
        ->whereYear('month', $time->year)
        ->first();

        if ($amountConfirm) {

            $amountConfirm->update([
                'confirm_amount' => $request->input('confirm_amount'),
                'percentage_on' => $request->input('confirm_percentage'),
                'quality' => $request->input('quality'),
                'note' => $request->input('note')
            ]);
        }
        else {
            AmountConfirm::create([
                'job_assign_id' => $jobAssign->id,
                'staff_id' => $staffId,
                'month' => $time,
                'confirm_amount' => $request->input('confirm_amount'),
                'request_amount' => $request->input('request_amount'),
                'percentage_on' => $request->input('confirm_percentage'),
                'quality' => $request->input('quality'),
                'note' => $request->input('note')
            ]);

            if ($jobAssign->isForward()) {
                AmountConfirm::create([
                    'job_assign_id' => $confirmAssigneeJobAssign->id,
                    'staff_id' => $confirmAssigneeJobAssign->job->assigner_id,
                    'month' => $time,
                    'confirm_amount' => 0,
                    'request_amount' => $request->input('confirm_amount'),
                    'percentage_on' => $request->input('confirm_percentage'),
                ]);
            }

        }
        
        return redirect()->back()->with('success', 'Xác nhận sản lượng thành công');
    }


    
}
