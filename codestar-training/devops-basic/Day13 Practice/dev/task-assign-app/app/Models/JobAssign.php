<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class JobAssign extends Pivot
{
    protected $table = 'job_assigns';

    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected $guarded = [];

    
    public function parent()
    {
        return $this->belongsTo(JobAssign::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(JobAssign::class, 'parent_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function assignee()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function workPlans()
    {
        return $this->hasMany(WorkPlan::class, 'job_assign_id');
    }

    public function processMethod()
    {
        return $this->belongsTo(ProcessMethod::class);
    }

    public function timeSheets()
    {
        return $this->hasMany(TimeSheet::class, 'job_assign_id');
    }

    public function amountConfirms()
    {
        return $this->hasMany(AmountConfirm::class, 'job_assign_id');
    }

    public function isDirectReport()
    {
        return $this->direct_report;
    }

    public function isChildOf($jobAssignId) 
    {
        return $this->parent_id == $jobAssignId;
    }

    public function isForwardOrAdditional() 
    {
        return $this->parent_id != null;
    }

    public function isAdditional()
    {
        return $this->is_additional;
    }

    public function isForward()
    {
        return $this->isForwardOrAdditional() && !$this->isAdditional();
    }

    public function getEvaluator()
    {
        $relations = $this->load('parent', 'parent.assignee', 'job', 'job.assigner');
        if ($relations->parent && !$this->isDirectReport()) {
            return $relations->parent->assignee->name;
        } 
        return $relations->job->assigner->name;
    }

    public function hasForwardChild()
    {
        $children = $this->load('children')->children;
        foreach ($children as $child) {
            if ($child->isForward()) {
                return true;
            }
        }
        return false;
    }

    public function sameAssigned($assigneeId, $processMethodId)
    {
        return $this->staff_id = $assigneeId && $this->process_method_id == $processMethodId;
    }

    public function calculateTotalWorkAmount($unit = 'manday')
    {
        $total = 0;
        $workPlans = $this->workPlans;
        foreach ($workPlans as $workPlan) {
            $total += $workPlan->calculateWorkAmount($unit);
        }
        return $total;
    }


    public function scopeDirectAssign($query, $staffId)
    {
        return $query->where([
            'staff_id' => $staffId,
            'parent_id' => null
        ])
        ->whereNotIn('status', ['pending', 'rejected'])
        ->whereDoesntHave('children', function($q) {
            $q->where('is_additional', 0);
        });
    }

    public function scopeMainJobAssign($query)
    {
        $mainProcessMethod = ProcessMethod::where('name', 'chủ trì')->first();
        return $query->where('process_method_id', $mainProcessMethod->id);
    }

    public function scopeBelongsToStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

}
