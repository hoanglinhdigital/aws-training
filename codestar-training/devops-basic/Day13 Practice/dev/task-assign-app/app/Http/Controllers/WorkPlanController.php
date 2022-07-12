<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobAssign;
use App\Models\ProcessMethod;
use App\Models\WorkPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class WorkPlanController extends Controller
{

    const DEFAULT_PAGINATE = 15;

    
    public function create(Request $request, $jobId) 
    {
        $type = $request->input('type');
        
        $staffId = Auth::user()->staff_id;

        $job = Job::find($jobId);

        if ($job->assigner_id == $staffId) {
            return view('jobs.workplan', ['jobId' => $jobId]);
        }

        $jobAssign = JobAssign::where([
            'job_id' => $jobId,
            'staff_id' => $staffId
        ])
        ->with([
            'workPlans',
            'processMethod'
        ])
        ->first();



        $workPlans = $jobAssign ? $jobAssign->workPlans : [];

        
        return view('jobs.workplan-create', compact('jobId', 'type', 'workPlans'));
    }


    public function show($jobId, $assigneeId=null) {
        
        $condition = ['job_id'=> $jobId];

        if ($assigneeId) {
            $condition['staff_id'] = $assigneeId;
        }
        
        
        $jobAssigns = JobAssign::where($condition)->with([
            'job.assigner',
            'assignee',
            'workPlans',
            'processMethod'
        ])->get();

        return response()->json($jobAssigns);

    }


    public function store(Request $request)
    {


        $validator = $this->makeValidator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }


        try {


            $jobId = $request->input('job_id');
            $type = $request->input('type');
            $staffId = Auth::user()->staff_id;

            $existingJobAssign = JobAssign::where([
                'job_id' => $jobId,
                'staff_id' => $staffId
            ])
            ->with([
                'job',
                'workPlans',
                'processMethod'
            ])
            ->first();

            $mainProcessMethod = ProcessMethod::where('name', 'chủ trì')->first();

            
            if ($existingJobAssign) {

                $existingJobAssign->workPlans()->create($request->all());

                if ($existingJobAssign->status == JobAssign::STATUS_ACTIVE) {
                    return redirect()->route('workplans.create', ['jobId' => $jobId])->with('success', 'Thêm kế hoạch công việc thành công');
                }

                $existingJobAssign->update(['status' => JobAssign::STATUS_ACTIVE]);

                $processMethod = $existingJobAssign->processMethod;
                
                if ($processMethod->id == $mainProcessMethod->id) {
                    $existingJobAssign->job()->update(['status' => Job::STATUS_ACTIVE]);
                }

                return redirect()->route('jobs.detail', [
                    'jobId' => $jobId,
                    'type' => $type
                ])
                ->with('success', 'Nhận việc thành công');                

            }
            else {

                $newJobAssign = JobAssign::create([
                    'job_id' => $jobId,
                    'staff_id' => $staffId,
                    'process_method_id' => $mainProcessMethod->id,
                    'status' => JobAssign::STATUS_ACTIVE
                ]);
                $newJobAssign->workPlans()->create($request->all());        
                
                $newJobAssign->job()->update(['status' => Job::STATUS_ACTIVE]);

                return redirect()->route('jobs.detail', [
                    'jobId' => $jobId,
                    'type' => $type
                ])
                ->with('success', 'Nhận việc thành công');  
            }

        } 
        catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã có lỗi xảy ra');
        }
        



    }


    public function delete(Request $request) {
        
        if ($request->has('job_assign_id')) {
            $request->session()->put('job_assign_id', $request->input('job_assign_id'));
        }

        if (!$request->has('workplan_ids')) {

            return redirect()->back()->withInput()->withErrors(['workplan', 'Chưa chọn kế hoạch để xóa']);

        }


        $workPlanIds = $request->input('workplan_ids');

        try {

            WorkPlan::whereIn('id', $workPlanIds)->delete();

            return redirect()->back()->with('success', 'Xóa kế hoạch thực hiện thành công');
        
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errorMessage', $e->getMessage()]);
        } 
            


    }

    private function makeValidator($data)
    {
        $rules = [            
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'from_time' => ['required', 'date_format:H:i'],
            'to_time' => ['required', 'date_format:H:i'], 
            'content' => ['required', 'string', 'max:255'],
            'job_id' => 'required', 

        ];
        $messages = [
            'from_date.required' => 'Trường từ ngày là bắt buộc',
            'to_date.required' => 'Trường đến ngày là bắt buộc',
            'to_date.after_or_equal' => 'Trường đến ngày phải sau trường từ ngày',
            'from_time.required' => 'Trường từ giờ là bắt buộc',
            'to_time.required' => 'Trường đến giờ là bắt buộc',
            'to_time.after_or_equal' => 'Trường đến giờ phải sau trường từ giờ',
            'content.required' => 'Trường nội dung là bắt buộc',

        ];

        $validator = Validator::make($data, $rules, $messages);
        $validator->sometimes('to_time', 'after_or_equal:from_time', function($input) {
            return $input->from_date == $input->to_date;
        });
        return $validator;
    }
}
