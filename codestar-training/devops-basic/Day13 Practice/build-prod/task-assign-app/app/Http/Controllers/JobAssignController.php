<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\ProcessMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobAssignController extends Controller
{
    const DEFAULT_PAGINATE = 15;
    
    const REDIRECT_WORKPLAN = 'REDIRECT_WORKPLAN';
    const ACCEPT_SUCCESSFUL = 'ACCEPT_SUCCESSFUL';
    const REJECT_SUCCESSFUL = 'REJECT_SUCCESSFUL';
    const REJECT_FAILURE = 'REJECT_FAILURE';
    const INTERNAL_ERROR = 'INTERNAL_ERROR';
    const EMPTY_DENY_REASON = 'EMPTY_DENY_REASON';

    

    public function index(Request $request) {
        
        $jobAssigns = [];

        if ($request->has('jobIds')) {

            $staffId = $request->input('staffId');
            $jobIds = $request->input('jobIds');

            $jobAssigns = JobAssign::whereIn('job_id', $jobIds)->with([
                'processMethod',
                'assignee',
                'job'
            ])
            ->get();

            foreach ($jobAssigns as $jobAssign) {
                $job = $jobAssign->job;
                $jobAssign->self_assigned = $job->assigner_id == $staffId;
            }
            
        }

        return response()->json($jobAssigns);
    }
    
    public function updateStatus(Request $request)
    {
        $action = $request->input('action');
        $jobId = $request->input('job_id');
        $type = $request->input('type');
        $denyReason = $request->input('deny_reason');
        $staffId = Auth::user()->staff_id;
        
        $job = Job::findOrFail($jobId);

        $jobAssign = JobAssign::where([
            'job_id' => $jobId, 
            'staff_id' => $staffId
        ])
        ->with('processMethod')
        ->first();

        switch ($action) {
            case 'accept':
                $result = $this->acceptJob($job, $staffId, $jobAssign);

                if ($result['status_code'] == self::REDIRECT_WORKPLAN) {
                    return redirect()->route('workplans.create', [
                        'jobId' => $jobId,
                        'type' => $type
                    ])
                    ->with('message', 'Nhập kế hoạch thực hiện để nhận việc');
                }

                if ($result['status_code'] == self::INTERNAL_ERROR) {
                    return redirect()->back()->with('error', $result['message']);
                }

                return redirect()->route('jobs.detail', [
                    'jobId' => $jobId,
                    'type' => $type
                ])
                ->with('success', $result['message']);

                    
            case 'reject': 
                $result = $this->rejectJob($job, $jobAssign, $denyReason);

                if ($result['status_code'] == self::EMPTY_DENY_REASON || $result['status_code'] == self::REJECT_SUCCESSFUL) {
                    return redirect()->route('jobs.detail', [
                        'jobId' => $jobId,
                        'type' => $type
                    ])
                    ->with('success', $result['message']);                
                }

                return redirect()->back()->with('error', $result['message']);

            case 'exchange': 
                //TODO: exchange function
                break;
        }
    }

    

    private function acceptJob($job, $staffId, $jobAssign)
    {
        $mainProcessMethod = ProcessMethod::where('name', 'chủ trì')->first();
        $workPlanRequired = Configuration::where('field', 'implementation_plan')->first('value')->value;
                
        if ($workPlanRequired) {
            return [
                'status_code' => self::REDIRECT_WORKPLAN
            ];
        }

        try {

            if ($jobAssign) {
                $jobAssign->update(['status' => 'active']);
    
                if ($jobAssign->processMethod->id == $mainProcessMethod->id) {
                    $job->update(['status' => 'active']);
                }
            }
            else {
                JobAssign::create([
                    'job_id' => $job->id,
                    'staff_id' => $staffId,
                    'process_method_id' => $mainProcessMethod->id,
                    'status' => 'active',
                ]);
    
                $job->update(['status' => 'active']);
            }
            return [
                'status_code' => self::ACCEPT_SUCCESSFUL,
                'message' => 'Nhận việc thành công'
            ];

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [
                'status_code' => self::INTERNAL_ERROR,
                'message' => 'Đã có lỗi xảy ra'
            ];
        }

    }

    private function rejectJob($job, $jobAssign, $denyReason)
    {
        $mainProcessMethod = ProcessMethod::where('name', 'chủ trì')->first();
        
        if (!$denyReason) {
            return [
                'status_code' => self::EMPTY_DENY_REASON,
                'message' => null
            ];
        }

        if ($jobAssign) {
            try {

                $jobAssign->update([
                    'status' => 'rejected',
                    'deny_reason' => $denyReason
                ]);
    
                if ($jobAssign->processMethod->id == $mainProcessMethod->id) {
                    $job->update(['status' => 'rejected']);
                }
                return [
                    'status_code' => self::REJECT_SUCCESSFUL,
                    'message' => 'Từ chối công việc thành công'
                ];

            } catch (Exception $e) {
                Log::error($e->getMessage());
                return [
                    'status_code' => self::INTERNAL_ERROR, 
                    'message' => 'Đã có lỗi xảy ra'
                ];
            }
        }
        return [
            'status_code' => self::REJECT_FAILURE,
            'message' => 'Không thể từ chối công việc không giao cho mình'
        ];

    }

}
