<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_DEACTIVE = 'deactive';
    const PERIOD_UNIT_DAY = 'day';
    const PERIOD_UNIT_WEEK = 'week';
    const PERIOD_UNIT_MONTH = 'month';
    const PERIOD_UNIT_QUARTER = 'quarter';
    const PERIOD_UNIT_YEAR = 'year';

    // Default attribute when create Model

    protected $attributes = [
        'status' => self::STATUS_PENDING,
        'period_unit' => self::PERIOD_UNIT_DAY,
    ];

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Job::class, 'parent_id');
    }

    public function type()
    {
        return $this->belongsTo(JobType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class, 'job_files');
    }

    public function assigner()
    {
        return $this->belongsTo(Staff::class, 'assigner_id');
    }

    public function assignees()
    {
        return $this->belongsToMany(Staff::class, 'job_assigns')->using(JobAssign::class)->withPivot(
            'process_method_id', 
            'parent_id', 
            'direct_report', 
            'sms', 
            'status', 
            'deny_reason',
            'deadline'
        );
    }

    public function jobAssigns()
    {
        return $this->hasMany(JobAssign::class, 'job_id');
    }


    public function updateHistories()
    {
        return $this->hasMany(UpdateJobHistory::class, 'job_id');
    }


    public function isActive()
    {
        return $this->status == 'active';
    }

    public function getRemaining()
    {
        $deadline = Carbon::parse($this->deadline);
        $now = Carbon::now();
        return $now->greaterThanOrEqualTo($deadline) ? 0 : $now->diffInDays($deadline);
    }

    public function calculateTimeSheetAmount()
    {
        $timeSheetAmount = 0;
        $jobAssigns = $this->load('jobAssigns')->jobAssigns;
        
        foreach ($jobAssigns as $jobAssign) {
            foreach ($jobAssign->timeSheets as $timeSheet) {
                $timeSheetAmount += $timeSheet->workAmountInManday();
            }
        }
        return $timeSheetAmount;

    }

    public function getFinishedPercent()
    {
        $jobAssigns = $this->load('jobAssigns.amountConfirms')->jobAssigns;
        $totalAmount = 0;
        foreach ($jobAssigns as $jobAssign) {
            $amountConfirms = $jobAssign->amountConfirms;
            $totalAmount += $amountConfirms->sum('confirm_amount');
        }
        $assignAmount = $this->assign_amount;
        if ($assignAmount) {
            return $totalAmount * 100 / ($assignAmount * 8);
        }
        return null;
    }

    public function getAssignees($returnText = false)
    {
        $assignees = $this->load('assignees')->assignees;
        if ($returnText) {
            $assigneeNames = $assignees->map(fn ($assignee) => $assignee->name);
            return implode(', ', $assigneeNames->toArray());
        }
        return $assignees;
    }

    public function getMainAssignee($returnText = false)
    {
        $mainJobAssign = JobAssign::with(
            'assignee',
            'assignee.account',
            'assignee.info'
        )
        ->where('job_id', $this->id)
        ->mainJobAssign()
        ->first();
        
        return $mainJobAssign ? ($returnText ? $mainJobAssign->assignee->name : $mainJobAssign->assignee) : null;
    }

    public function getOtherAssignees($returnText = false)
    {
        $mainAssignee = $this->getMainAssignee();
        $assignees = $this->load('assignees')->assignees;
        
        if (!$mainAssignee) {
            $others = $assignees;
        }
        else {
            $others = $assignees->filter(function($assignee) use ($mainAssignee) {
                return $assignee->id != $mainAssignee->id;
            });
        }

        if ($returnText) {
            $otherNames = $others->map(fn ($assignee) => $assignee->name);
            return implode(', ', $otherNames->toArray());
        }
        return $others;
    }


    public function makeJobUpdates($newJobData, $updateNote)
    {
        $jobUpdates = [];

        foreach ($newJobData as $key => $value) {
            if ($value != $this->$key) {
                switch ($key) {
                    case "assigner_id":  
                        $assigner = Staff::find($this->assigner_id);
                        $newAssigner = Staff::find($value);
                        
                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Người giao việc',
                            "old_value" => $assigner->name,
                            "new_value" => $newAssigner->name,
                            "note" => $updateNote
                        ]);    
                             
                        break;

                    case "project_id": 
                        $project = Project::find($this->project_id);
                        $newProject = Project::find($value);

                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Dự án',
                            "old_value" => $project ? $project->name : '',
                            "new_value" => $newProject ? $newProject->name : '',
                            "note" => $updateNote
                        ]);
                        break;

                    case "parent_id": 
                        
                        $parentJob = Job::find($this->parent_id);
                        $newParentJob = Job::find($value);
                        
                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Việc cha',
                            "old_value" => $parentJob ? $parentJob->name : '',
                            "new_value" => $newParentJob ? $newParentJob->name : '',
                            "note" => $updateNote
                        ]);
                        break;

                    case "job_type_id": 
                        
                        $jobType = JobType::find($this->job_type_id);
                        $newJobType = JobType::find($value);
                        
                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Loại công việc',
                            "old_value" => $jobType ? $jobType->name : '',
                            "new_value" => $newJobType ? $newJobType->name : '',
                            "note" => $updateNote
                        ]);
                        break;

                    case "process_method_id":
                        
                        $processMethod = ProcessMethod::find($this->process_method_id);
                        $newProcessMethod = ProcessMethod::find($value);
                        
                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Hình thức xử lý',
                            "old_value" => $processMethod ? $processMethod->name : '',
                            "new_value" => $newProcessMethod ? $newProcessMethod->name: '',
                            "note" => $updateNote
                        ]);
                        break;

                    case "priority_id": 
                        
                        $priority = Priority::find($this->priority_id);
                        $newPriority = Priority::find($value);
                        
                        array_push($jobUpdates, [
                            "job_id" => $this->id,
                            "field" => 'Loại công việc',
                            "old_value" => $priority ? $priority->name : '',
                            "new_value" => $newPriority ? $newPriority->name : '',
                            "note" => $updateNote
                        ]);
                        break;

                    default: 
                        array_push($jobUpdates, [
                            "job_id" => $this->id, 
                            "field" => $key, 
                            "old_value" => $this->$key ?? '',
                            "new_value" => $value ?? '', 
                            "note" => $updateNote
                        ]);

                        
                }
            }
            

        }
        return $jobUpdates;
    }

    public function scopeHasJobAssign($query, $jobAssignId) 
    {
        return $query->whereHas('jobAssigns', fn ($subQuery) => $subQuery->where('id', $jobAssignId));
    }



}
