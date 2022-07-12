<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    use HasFactory;
    protected $fillable = ['job_assign_id', 'from_date', 'to_date', 'from_time', 'to_time', 'content'];
    public function jobAssign()
    {
        return $this->belongsTo(JobAssign::class, 'job_assign_id');
    }

    public static function isOverAssignAmount($requestInput, $jobId, $assignAmount, $timeSheetId = null)
    {
        $requestTimeSheet = new TimeSheet($requestInput);
        $requestAmount = $requestTimeSheet->workAmountInHour();
        
        $timeSheetsTillNow = TimeSheet::whereHas('jobAssign.job', function ($query) use ($jobId, $timeSheetId) {
            $query->where('job_id', $jobId);
        })
        ->beforeTime($requestInput['to_date'], $requestInput['to_time'])
        ->get();
        
        if ($timeSheetId) {
            $timeSheetsTillNow = $timeSheetsTillNow->filter(function ($timeSheet) use ($timeSheetId) {
                return $timeSheet->id != $timeSheetId;
            });
        }

        $totalTimeSheetAmount = $requestAmount;
        foreach($timeSheetsTillNow as $timeSheet) {
            $totalTimeSheetAmount += $timeSheet->workAmountInHour();
        }
        return $totalTimeSheetAmount > ($assignAmount * 8);
    }

    public function timeDate()
    {
        $fromTime = strtotime($this->from_time);
        $toTime = strtotime($this->to_time);

        $fromDate = strtotime($this->from_date);
        $toDate = strtotime($this->to_date);

        $absHour = abs($toTime - $fromTime);
        $hour = floor($absHour/(60*60));

        $absDate = floor($toDate - $fromDate);
        $date = floor($absDate/(60*60*24));

        $dateDiff = $hour*($date+1);

        if ($this->jobAssign->job->period) {
            $jod_period = $this->jobAssign->job->period*8;
            return number_format(($dateDiff/$jod_period)*100, 2, '.', '');
        }
        return null;

    }

    public function workAmountInHour()
    {
        $fromDate = strtotime($this->from_date);
        $toDate = strtotime($this->to_date);
        
        if (!$this->from_time && !$this->to_time) {
            $absHour = 8;
        }
        else {
            $fromTime = strtotime($this->from_time);
            $toTime = strtotime($this->to_time);
            $absHour = abs($toTime - $fromTime);
        }
        
        $hour = floor($absHour / (60 * 60));

        $absDate = floor($toDate - $fromDate);
        $date = floor($absDate / (60 * 60 * 24));

        $workAmount = $hour * ($date + 1);
        return $workAmount;
    }
    
    public function workAmountInManday() 
    {
        
        $fromDate = strtotime($this->from_date);
        $toDate = strtotime($this->to_date);
        
        if (!$this->from_time && !$this->to_time) {
            $absHour = 8;
        }
        else {
            $fromTime = strtotime($this->from_time);
            $toTime = strtotime($this->to_time);
            $absHour = abs($toTime - $fromTime);
        }
        
        $hour = floor($absHour / (60 * 60));

        $absDate = floor($toDate - $fromDate);
        $date = floor($absDate / (60 * 60 * 24));

        $workAmount = $hour * ($date + 1) / 8;
        return $workAmount;
    }

    public function getPercentageCompleted()
    {
        $timeSheetsTillNow = TimeSheet::where('job_assign_id', $this->job_assign_id)
            ->beforeTime($this->to_date, $this->to_time)
            ->get();

        $totalTimeSheetAmount = 0;
        foreach ($timeSheetsTillNow as $timeSheet) {
            $totalTimeSheetAmount += $timeSheet->workAmountInHour();
        }

        $job = $this->load('jobAssign.job')->jobAssign->job;
        return $job->assign_amount ? $totalTimeSheetAmount * 100 / ($job->assign_amount * 8) : null;
    }

    public function scopeBelongsToJob($query, $jobId)
    {
        return $query->whereHas('jobAssign', function($q) use ($jobId){
            $q->where('job_id', $jobId);
        });
    }

    public function scopeBelongsToAssignee($query, $assigneeId)
    {
        return $query->whereHas('jobAssign', function($q) use ($assigneeId){
            $q->where('staff_id', $assigneeId);
        }); 
    }

    public function scopeBelongsToJobAssign($query, $jobId, $assigneeId)
    {
        return $query->belongsToJob($jobId)->belongsToAssignee($assigneeId);
    }

    public function scopeBeforeTime($query, $toDate, $toTime)
    {
        return $query->where('to_date', '<', $toDate)
            ->orWhere('to_date', $toDate)
            ->where('to_time', '<=', $toTime);
    }
}
