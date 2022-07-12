<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\File;
use App\Models\Job;
use App\Models\JobAssign;
use App\Models\JobType;
use App\Models\Priority;
use App\Models\ProcessMethod;
use App\Models\Project;
use App\Models\Staff;
use App\Models\UpdateJobHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;


class JobsController extends Controller
{
    const DEFAULT_PAGINATE = 20;
    const MAIN_ASSIGNEE = 1;
    const TIME_TO_LIVE = 10; 

    public function index(Request $request, $condition = [])
    {
        $staffId = Auth::user()->staff_id;

        $jobTypes = JobType::orderBy('name')->get();
        $assigners = Staff::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();


        $type = $request->input('type') ?? 'all';

        if ($type == 'all') {
            $jobs = $this->getAllJobsByStaffId($staffId, $condition);
            return view('jobs.all-jobs', compact('jobs', 'jobTypes', 'assigners', 'projects'));
        }

        $typeToViewMapping = [
            'pending' => 'jobs.pending-jobs',
            'handling' => 'jobs.handling-jobs',
            'assigner' => 'jobs.assigner-jobs'
        ];

        $typeToFuncMapping = [
            'pending' => 'getPendingJobs',
            'handling' => 'getHandlingJobs',
            'assigner' => 'getAssignerJobs',
        ];

        $mappedFunc = $typeToFuncMapping[$type];
        [$leftTableJobs, $rightTableJobs] = $this->$mappedFunc($staffId, $condition);
        
        $request->flash();
        return view($typeToViewMapping[$type], compact('leftTableJobs', 'rightTableJobs', 'jobTypes', 'assigners', 'projects', 'type'));
        

    }

    public function search(Request $request)
    {
        $action = $request->input('action');
        $type = $request->input('type');
        if ($action == 'reset') {
            if ($type == 'all') {
                return redirect()->route('jobs.index');
            }
            return redirect()->route('jobs.index', ['type' => $type]);
        }
        
        foreach ($request->all() as $key => $value) {
            if (!in_array($key, ['_token', 'action', 'type', 'from_date', 'to_date']) && $value)
                $condition[$key] = $value;
            
            if ($key == 'from_date' && $value) {
                $condition[] = ['deadline', '>=' , $value];
            }

            if ($key == 'to_date' && $value) {
                $condition[] = ['deadline', '<=', $value];
            } 

        }
        return $this->index($request, $condition);
    }

    public function show(Request $request, $id)
    {

        $job = Job::with([
            'parent', 
            'type', 
            'project', 
            'priority', 
            'assigner',
            'assignees',
            'jobAssigns.processMethod',
            'files',
        ])
        ->where('id', $id)
        ->first();

        $status = $job->status;

        $job->status = __('job.all_status.' . $status);

        $jobAssigns = $job->jobAssigns;        
        
        foreach($job->assignees as $assignee) {
            $id = $assignee->id;
            $processMethodId = $assignee->pivot->process_method_id;
            
            $jobAssign = $jobAssigns->filter(fn ($jobAssign) => $jobAssign->sameAssigned($id, $processMethodId))->first();
        
            $assignee->pivot->process_method = $jobAssign->processMethod->name;

        }

        return response($job);
    }

    public function create(Request $request, $jobId=null)
    {
        
        $staffId = Auth::user()->staff_id;

        $parentJobId = $request->input('parentId');

        $relatedJobs = Job::with(
            'jobAssigns'
        )
        ->where('assigner_id', $staffId)
        ->whereNotIn('status', ['finished', 'canceled'])
        ->orWhereHas('jobAssigns', function ($query) use ($staffId) {
            $query->where([
                'staff_id' => $staffId,
                'status' => 'accepted'
            ]);
        })
        ->get();

        $createdJobs = Job::where('assigner_id', $staffId)
        ->orderBy('created_at', 'DESC')
        ->paginate($this::DEFAULT_PAGINATE);

        //TODO: cache frequently queried models like projects, jobtypes, priorities and process methods
        // using Cache facade to remember cache data and add to cache if there's no data with given keys
        // update Cache data on update these catagories in databse
        $staff = Staff::all();
        $projects = Project::all();
        $jobTypes = JobType::all();
        $priorities = Priority::all();
        $processMethods = ProcessMethod::all();
        

        $systemConfig = $this->formatSystemConfig(Configuration::getSystemConfiguration());
        
        return view('jobs.create', compact('relatedJobs', 'createdJobs', 'staff', 'projects', 'jobTypes', 'priorities', 'processMethods', 'jobId', 'parentJobId', 'systemConfig'));

    }

    public function detail(Request $request, $jobId) 
    {
        $type = $request->input('type') ?? 'all';
        $staffId = Auth::user()->staff_id;
                    
        $job = Job::find($jobId);

        if ($job->assigner_id == $staffId) {
            return redirect()->route('jobs.create', ['jobId' => $jobId]);
        }

        $typeToFuncMapping = [
            'pending' => 'getPendingJobs',
            'handling' => 'getHandlingJobs',
            'assigner' => 'getAssignerJobs',
        ];

        if ($type == 'all') {
            $createdJobs = Job::where('assigner_id', $staffId)
            ->orderBy('created_at', 'DESC')
            ->get();
            return view('jobs.job-detail', [
                'jobId' => $jobId,
                'numTables' => 1,
                'table' => $createdJobs
            ]);
        }
        
        $mappedFunc = $typeToFuncMapping[$type];
        [$leftTableJobs, $rightTableJobs] = $this->$mappedFunc($staffId);

        return view('jobs.job-detail', [
            'jobId' => $jobId,
            'numTables' => 2,
            'leftTable' => $leftTableJobs,
            'rightTable' => $rightTableJobs,
            'type' => $type,
            'success' => $request->session()->get('success')
        ]);

    }

    public function detailAction(Request $request)
    {   
        $action = $request->input('action');
        
        $jobIds = $request->input('job_ids');

        if ($action != 'assign' && count($jobIds) > 1) {
            return redirect()->back()->with('error', 'Chọn duy nhất một công việc');
        }

        switch ($action) {
            
            case 'detail':
                $jobId = $jobIds[0];
                $type = $request->input('type');
                return redirect()->route('jobs.detail', ['jobId' => $jobId, 'type' => $type]);
  
            case 'finish':
                // TODO: finish function
                break;

            case 'search': 
                return redirect()->route('jobs.index');

            case 'assign':
                return redirect()->route('assignee-list.index', [
                    'jobIds' => $jobIds,
                ]);
            
            case 'timesheet': 
                if (!$jobIds) {
                    return redirect()->route('timesheet.create');
                }
                $jobId = $jobIds[0];
                return redirect()->route('timesheet.create', ['job_id' => $jobId]);

            case 'amount_confirm': 
                $jobId = $jobIds[0];
                return redirect()->route('amount-confirms.create', ['job_id' => $jobId]);
                break;

            case 'job_create': 
                $jobId = $jobIds[0];
                return redirect()->route('jobs.create', ['parentId' => $jobId]);
    

            case 'exchange': 
                // TODO: exchange view function
                break;
        }
    }

    public function action(Request $request)
    {

        $action = $request->input('action');
        
        switch ($action) {
            case 'save':
                return $this->save($request->all());

            case 'save_copy': 
                return $this->save($request->all(), true);

            case 'reset': 
                return redirect()->route('jobs.create');

            case 'delete': 
                $id = $request->input('job_id');
                return $this->destroy($id);
                
            case 'search': 
                return redirect()->route('jobs.index');

        }
    }
    
    private function destroy ($id) 
    {

        if (!$id) {
            return redirect()->route('jobs.create')->withInput()->with('error', 'Chưa chọn công việc để xóa');
        }
        
        $job = Job::find($id);
        try {
            
            if ($job) {
                
                $job->delete();
                return redirect()->route('jobs.create')->with('success', 'Xóa công việc thành công');
            
            }
            else {
                
                return redirect()->route('jobs.create')->withInput()->with('error', 'Công việc không tồn tại');

            }

        }
        catch (Exception $e) {
            return redirect()->route('jobs.create')->withInput()->with('error', 'Đã có lỗi xảy ra');
        }
        
    } 

    private function save($data, $flashInputs=false) {

        $jobId = $data['job_id'];

        $systemConfig = $this->formatSystemConfig(Configuration::getSystemConfiguration());

        $validator = $this->makeValidator($data, $systemConfig);
        if ($validator->fails()) {
            if ($jobId) {
                return redirect()->route('jobs.create', ['jobId' => $jobId])->withErrors($validator->errors());
            }
            return redirect()->route('jobs.create')->withInput()->withErrors($validator->errors());
        }

        $tableCols = DB::getSchemaBuilder()->getColumnListing('jobs');
        $jobData = array_filter(
            $data, 
            fn($key) => in_array($key, $tableCols) && $key != 'status',
            ARRAY_FILTER_USE_KEY
        );
        
        try {
            if ($jobId) {

                $job = Job::findOrFail($jobId);

                if ($job->isActive()) {

                    $updateNote = $data['note'] ?? '';

                    $jobUpdates = $job->makeJobUpdates($jobData, $updateNote);

                    if ($jobUpdates) {
                        $job->updateHistories()->saveMany($jobUpdates);
                    }
                }

                $job->update($jobData);
                
            }
            else {
                if ($systemConfig['get_job']) {
                    $jobData['status'] = Job::STATUS_ACTIVE;
                }
                $job = Job::create($jobData);
            }
        
            if (isset($data['job_files'])) {
                
                $files = $data['job_files'];

                foreach ($files as $file) {
                    
                    $originalFileName = ($file->getClientOriginalName());
                    $file->storeAs(File::UPLOAD_DIR, $originalFileName, 'public');

                    $newFile = File::create([
                        'staff_id' => $job->assigner_id, 
                        'name' => $originalFileName,
                        'dir' => File::UPLOAD_DIR
                    ]);
                    $job->files()->attach($newFile->id);
                  

                }
            }

            if ((isset($data['phoi-hop']) || isset($data['nhan-xet'])) && !isset($data['chu-tri'])) {
                return redirect()->route('jobs.create')->withInput()->with('error', 'Vui lòng chọn người chủ trì');
            }
    
            $processMethods = [
                'chu-tri' => 'chủ trì',
                'phoi-hop' => 'phối hợp',
                'nhan-xet' => 'nhận xét'
            ];
    
    
                
            foreach ($processMethods as $key => $value) {
                if (isset($data[$key])) {
                    
                    $processMethod = ProcessMethod::where('name', $value)->first();
                    
                    $result = $this->assignJob($data[$key], $processMethod, $job->id);
    
                    if (!$result['success']) {
                        return redirect()->route('jobs.create')->withInput()->with('error', 'Đã có lỗi xảy ra');
                    }
                }
            }
    
            $message = $jobId ? 'Sửa công việc thành công' : 'Thêm công việc thành công';
    
            if ($jobId || $flashInputs) {
                
                return redirect()->route('jobs.create', ['jobId' => $jobId ?? $job->id])->with('success', $message);
            }
            return redirect()->route('jobs.create')->with('success', $message);
        }
        catch (Exception $e) {
            return redirect()->route('jobs.create')->withInput()->with('error', 'Đã có lỗi xảy ra');
        }
    }


    private function makeValidator($data, $systemConfig)
    {
        $jobId = $data['job_id'];

        $rules = [ 
            'assigner_id' => 'required', 
            'name' => ['required', 'string'], 
            'deadline' => ['required', 'date'],
            'code' => $systemConfig['job_code'] ? 'required' : '',
            'lsx_amount' => $systemConfig['production_volume'] ? 'required' : '',
            'assign_amount' => $systemConfig['volume_interface'] ? 'required' : '',
        ];

        if (!$jobId) {
            $rules['code'] = $systemConfig['job_code'] ? ['required', 'unique:jobs'] : '';
        }


        $messages = [
            'code.unique' => 'Mã dự án đã được sử dụng',
            'code.required' => 'Mã dự án là bắt buộc',
            'assigner_id.required' => 'Người giao việc là bắt buộc',
            'name.required' => 'Tên công việc là bắt buộc',
            'deadline.required' => 'Hạn xử lý là bắt buộc',
            'deadline.date' => 'Hạn xử lý phải là ngày',
            'period.required' => 'Kỳ là bắt buộc',
            'lsx_amount.required' => 'Khối lượng LSX là bắt buộc',
            'assign_amount.required' => 'Khối lượng giao là bắt buộc',
        ];

        $validator = Validator::make($data, $rules, $messages);
        
        $validator->sometimes('period', 'required', function() use ($data, $systemConfig) {
            $jobTypeId = $data['job_type_id'];
            if ($jobTypeId) {
                $jobType = JobType::find($jobTypeId);
                return $jobType->common && $systemConfig['period'];
            }
            return false;
        });
        $validator->sometimes('code', 'unique:jobs', fn ($input) => !$jobId && $input->code != null);

        return $validator;
    }

    

    private function assignJob($assignees, $processMethod, $jobId) {
        $mainProcessMethod = ProcessMethod::where('name', 'chủ trì')->first();
        try {
            if ($processMethod->id == $mainProcessMethod->id) {
                JobAssign::where([
                    'job_id' => $jobId,
                    'process_method_id' => $mainProcessMethod->id
                ])->delete();
            }
            foreach($assignees as $assignee) {
            
                $assignee = json_decode($assignee, true);
                
                JobAssign::updateOrCreate(
                    [
                        'job_id' => $jobId,
                        'staff_id' => $assignee['staff_id'],
                        'process_method_id' => $processMethod->id,
                    ], 
                    [
                        'direct_report' => $assignee['direct_report'] ?? null,
                        'deadline' => $assignee['deadline'] ?? null,
                        'sms' => $assignee['sms'] ?? null,
                        'status' => JobAssign::STATUS_PENDING
                    ]
                );
    
            }
            return ['success' => true];
        } 
        catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }



    private function getHandlingJobs($staffId, $condition=[]) 
    {

        $directJobs = Job::with([
            'jobAssigns' => function ($query) use ($staffId){
                $query->directAssign($staffId)
                    ->whereNotIn('status', ['pending', 'rejected']);
            }, 
            'jobAssigns.processMethod',
            'jobAssigns.timeSheets',
            'project:id,code', 
            'assigner:id,name', 
        ])
        ->where($condition)
        ->whereNotIn('status', ['pending', 'finished', 'canceled'])
        ->whereHas('jobAssigns', function ($query) use ($staffId) {
            $query->directAssign($staffId)
                ->whereNotIn('status', ['pending', 'rejected']);
        })->get();

        $relatedJobs = Job::with([
            'jobAssigns' => function ($query) use ($staffId){
                $query->where('staff_id', $staffId)
                    ->whereNotNull('parent_id')
                    ->whereNotIn('status', ['pending', 'rejected']);
            }, 
            'jobAssigns.parent.assignee:id,name',
            'jobAssigns.processMethod',
            'project:id,code', 
            'assigner:id,name', 
        ])
        ->where($condition)
        ->whereNotIn('status', ['pending', 'finished', 'canceled'])
        ->whereHas('jobAssigns', function ($query) use ($staffId) {
            $query->where('staff_id', $staffId)
                ->whereNotNull('parent_id')
                ->whereNotIn('status', ['pending', 'rejected']);
        })->get();

        $reformatedDirectJobs = $this->reformatHandlingJobs($directJobs);
        $reformatedRelatedJobs = $this->reformatHandlingJobs($relatedJobs, true);
        
        return [$reformatedDirectJobs, $reformatedRelatedJobs];
        
    }

    private function getPendingJobs($staffId, $condition=[]) 
    {
        $newAssignedJobs = Job::with([
            'jobAssigns' => function ($query) use ($staffId) {
                $query->where([
                    'staff_id' => $staffId,
                    'status' => 'pending'
                ]);
            }
        ])
        ->where($condition)
        ->whereHas('jobAssigns', function ($query) use ($staffId) {
            $query->where([
                'staff_id' => $staffId,
                'status' => 'pending'
            ]);
        })->get();




        $unassignedJobs = Job::where('status', 'pending')
        ->where($condition)
        ->doesntHave('jobAssigns')
        ->get();
        
        $reformatedNewAssignedJobs = $this->reformatPendingJobs($newAssignedJobs);    
        $reformatedUnassignedJobs = $this->reformatPendingJobs($unassignedJobs);
        
        return [$reformatedNewAssignedJobs, $reformatedUnassignedJobs];
    }

    private function getAssignerJobs($staffId, $condition=null) 
    {
        $createdJobs = Job::where('assigner_id', $staffId)->with([
            'project:id,code',
            'assigner:id,name'
        ])->get();
        

        $forwardJobs = Job::with([
            'project:id,code',
            'assigner:id,name'
        ])
        ->whereHas('jobAssigns', function($query) use ($staffId){
            $query->where('staff_id', $staffId);
        })
        ->whereHas('jobAssigns.children')
        ->get();

        $reformatedCreatedJobs = $this->reformatAssignerJobs($createdJobs);        
        $reformatedForwardJobs = $this->reformatAssignerJobs($forwardJobs);

        return [$reformatedCreatedJobs, $reformatedForwardJobs];
    }

    private function getAllJobsByStaffId($staffId, $condition=null)
    {

        $allJobs = Job::with([
            'jobAssigns',
            'assignees',
        ])
        ->where($condition)
        ->whereNotIn('status', ['rejected', 'cancelled'])
        ->where(function ($subQuery) use ($staffId){  
            
            $subQuery->where('assigner_id', $staffId)->orWhereHas('jobAssigns', function ($query) use ($staffId){
                $query->where('staff_id', $staffId);
            });

        })
        ->get();
        
        $reformatedAllJobs = $this->reformatAllJobs($allJobs);
        return $reformatedAllJobs;



    }


    private function reformatHandlingJobs($jobs, $related = false) 
    {

        foreach ($jobs as $job) {
            $job->project_code = $job->project ? $job->project->code : null;
            $job->assigner = $job->assigner->name;
            $job->process_method  = $job->jobAssigns[0]->processMethod->name;
            
            $job->job_assign_id = $job->jobAssigns[0]->id;
            if ($related) {
                $job->forward = $job->jobAssigns[0]->parent->assignee->name;
            }

            $job->remaining = $job->getRemaining();

            $job->timesheet_amount = $job->calculateTimeSheetAmount();
            $job->finished_percent = $job->assign_amount ? $job->timesheet_amount / $job->assign_amount : null;
        }
        return $jobs;
    }

    private function reformatPendingJobs($jobs)
    {
        foreach ($jobs as $item) {
            
            $item->project_code = $item->project ? $item->project->code : null;
            $item->assigner = $item->assigner->name; 
            if ($item->jobAssigns->count() > 0) {
                $item->job_assign_id = $item->jobAssigns[0]->id;
            }
        }

        return $jobs;
    }

    private function reformatAssignerJobs($jobs)
    {
        foreach ($jobs as $job) {
            $job->project_code = $job->project ? $job->project->code : null;
            $job->assigner = $job->assigner->name;
            $job->main_assignee = $job->getMainAssignee(true);
            $job->others = $job->getOtherAssignees(true);
            $job->remaining = $job->getRemaining();
            $job->evaluation = null;
            $job->status = __('job.all_status.' . $job->status, [], 'vi');

        }

        return $jobs;
    }

    public function reformatAllJobs($jobs)
    {

        foreach ($jobs as $job) {

            $job->project_code = $job->project ? $job->project->code : null;
            $job->assigner = $job->assigner->name;
            $job->main_assignee = $job->getMainAssignee(true);
            $job->others = $job->getOtherAssignees(true);
            $job->remaining = $job->getRemaining();
            $job->evaluation = null;
            $job->status = __('job.all_status.' . $job->status, [], 'vi');

        }

        return $jobs;
    }

    private function formatSystemConfig($systemConfigs)
    {
        $reformat = [];
        foreach ($systemConfigs as $config) {
            $reformat[$config->field] = $config->value;
        }
        return $reformat;
    }

    


}
