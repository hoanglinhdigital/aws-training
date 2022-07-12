<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobAssign;
use App\Models\ProcessMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssigneeListController extends Controller
{
    const VALIDATION_FAIL = 'VALIDATION_FAIL';
    const SAVE_SUCCESSFUL = 'SAVE_SUCCESSFUL';
    const DELETE_SUCCESSFUL = 'DELETE_SUCCESSFUL';
    const INTERNAL_ERROR = 'INTERNAL_ERROR';
    const JOB_NOT_FOUND = 'JOB_NOT_FOUND';


    public function index(Request $request)
    {
        $jobs = [];



        if (!$request->has('jobIds')) {
            return view('jobs.assignee-list', compact('jobs', 'processMethods'));
        }

        $jobIds = $request->input('jobIds');
        $jobs = Job::whereIn('id', $jobIds)->with('assigner')->get();

        if ($jobs->count() == 0) {
            return redirect()->back()->withErrors(['job_id' => 'Không tìm thấy công việc']);
        }

        foreach ($jobs as $job) {
            $job->assigner_name = $job->assigner->name;
        }

        $staffId = Auth::user()->staff_id;

        if ($jobs[0]->assigner_id == $staffId) {
            $processMethods = ProcessMethod::whereNotIn('name', ['chuyển tiếp'])->get();
        } else {

            $jobAssign = JobAssign::where([
                'job_id' => $job->id,
                'staff_id' => $staffId
            ])
                ->with('processMethod')
                ->first();

            $processMethod = $jobAssign->processMethod;

            if ($processMethod->assigner) {
                $processMethods = ProcessMethod::whereNotIn('name', [
                    $processMethod->name,
                ])->get();
            } else {
                $processMethods = ProcessMethod::whereIn('name', [
                    $processMethod->name,
                    'chuyển tiếp'
                ])->get();
            }
        }

        return view('jobs.assignee-list', compact('jobs', 'processMethods'));
    }


    public function show($jobId)
    {
        $job = Job::with([
            'assigner',
            'jobAssigns',
            'jobAssigns.processMethod',
            'jobAssigns.assignee',
        ])
            ->where('id', $jobId)
            ->first();

        $assigneeList = $this->reformat($job);

        return response()->json($assigneeList);
    }


    public function action(Request $request)
    {
        $action = $request->input('action');
        return $this->$action($request);
    }



    public function save($request)
    {

        if ($request->has('old_job_assigns')) {
            $result = $this->updateOldJobAssigns($request);

            if ($result['status_code'] == self::INTERNAL_ERROR) {
                return redirect()->back()->with('error', $result['message']);
            }
        } else {
            $validator = $this->makeValidator($request);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
        }

        if (!$request->has('job_assigns')) {
            return redirect()->back()->with('success', 'Cập nhật giao xử lý thành công');
        }

        $result = $this->createNewJobAssigns($request);

        if ($result['status_code'] != self::SAVE_SUCCESSFUL) {
            return redirect()->back()->with('error', $result['message']);
        }
        return redirect()->back()->with('success', $result['message']);
    }

    public function delete($request)
    {

        if (!$request->has('delete_ids')) {
            return redirect()->back()->with('error', 'Chưa chọn đối tượng để xóa');
        }

        $jobAssignIds = $request->input('delete_ids');

        try {

            JobAssign::whereIn('id', $jobAssignIds)->delete();
            return redirect()->back()->with('success', 'Xóa đối tượng xử lý thành công');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra');
        }
    }

    private function updateOldJobAssigns($request)
    {
        $oldJobAssignUpdates = array_map(function ($jobAssign) {
            return json_decode($jobAssign, true);
        }, $request->input('old_job_assigns'));

        try {

            foreach ($oldJobAssignUpdates as $update) {
                $jobAssignId = $update['id'];

                $oldJobAssign = JobAssign::find($jobAssignId);
                $oldJobAssign->update([
                    'sms' => $update['sms'],
                    'direct_report' => $update['direct_report'],
                    'deadline' => $update['deadline'],
                ]);
            }
            return [
                'status_code' => self::SAVE_SUCCESSFUL,
                'message' => 'Cập nhật giao xử lý thành công'
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [
                'status_code' => self::INTERNAL_ERROR,
                'message' => 'Đã có lỗi xảy ra'
            ];
        }
    }

    private function makeValidator($request)
    {
        $rules = [
            'job_assigns' => 'required',
            'job_ids' => 'required',
        ];

        $messages = [
            'job_assigns.required' => 'Chưa chọn đối tượng xử lý',
            'job_ids.required' => 'Chưa chọn công việc nào'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    private function createNewJobAssigns($request)
    {
        $jobAssigns = $request->input('job_assigns');
        $jobAssigns = array_map(function ($item) {
            return json_decode($item, true);
        }, $jobAssigns);

        $jobIds = $request->input('job_ids');
        $staffId = Auth::user()->staff_id;

        try {
            $jobs = Job::whereIn('id', $jobIds)->with('jobAssigns')->get();

            if ($jobs->count() == 0) {
                return [
                    'status_code' => self::JOB_NOT_FOUND,
                    'message' => 'Không tìm thấy công việc nào'
                ];
            }

            $forwardProcessMethod = ProcessMethod::where('name', 'chuyển tiếp')->first();
            $forwardJobAssigns = array_filter($jobAssigns, function ($jobAssign) use ($forwardProcessMethod) {
                return intval($jobAssign['process_method_id']) == $forwardProcessMethod->id;
            });

            $otherJobAssigns = array_filter($jobAssigns, function ($jobAssign) use ($forwardProcessMethod) {
                return intval($jobAssign['process_method_id']) != $forwardProcessMethod->id;
            });

            foreach ($jobs as $job) {

                if ($job->assigner_id == $staffId) {
                    $job->jobAssigns()->createMany($otherJobAssigns);
                } else {
                    $currentJobAssign = JobAssign::where([
                        'job_id' => $job->id,
                        'staff_id' => $staffId
                    ])->first();

                    $mappedForwardJobAssigns = array_map(function ($jobAssign) use ($currentJobAssign) {

                        $jobAssign['parent_id'] = $currentJobAssign->id;
                        $jobAssign['process_method_id'] = $currentJobAssign->process_method_id;

                        return $jobAssign;
                    }, $forwardJobAssigns);

                    $mappedOtherJobAssigns = array_map(function ($jobAssign) use ($currentJobAssign) {

                        $jobAssign['parent_id'] = $currentJobAssign->id;
                        $jobAssign['is_additional'] = true;

                        return $jobAssign;
                    }, $otherJobAssigns);

                    $newJobAssigns = array_merge($mappedForwardJobAssigns, $mappedOtherJobAssigns);


                    $job->jobAssigns()->createMany($newJobAssigns);
                }
            }

            return [
                'status_code' => self::SAVE_SUCCESSFUL,
                'message' => 'Giao xử lý thành công'
            ];
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [
                'status_code' => self::INTERNAL_ERROR,
                'message' => 'Đã có lỗi xảy ra'
            ];
        }
    }


    private function reformat($job)
    {
        $reformat = [];

        $assigner = $job->assigner->name;


        foreach ($job->jobAssigns as $jobAssign) {
            $reformatObj = [
                'assigner' => $assigner,
                'evaluator' => $jobAssign->getEvaluator(),
                'assignee' => $jobAssign->assignee->name,
                'process_method' => $jobAssign->processMethod->name,
                'history' => null, //TODO: get forward histories
                'status' => __('job-assign.status.' . $jobAssign->status)
            ];

            $reformat[] = $reformatObj;
        }

        return $reformat;
    }
}
