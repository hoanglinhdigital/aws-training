<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPlan extends Model
{
    protected $fillable = ['job_assign_id', 'from_date', 'to_date', 'from_time', 'to_time', 'content'];

    public function jobAssigned()
    {
        return $this->belongsTo(JobAssign::class);
    }

    public function calculateWorkAmount($unit = 'manday') {
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
        return $unit == 'manday' ? $workAmount / 8 : $workAmount;
    }


    public function scopeBetweenDate($query, $fromDate, $toDate)
    {

        if (!$fromDate || !$toDate) {
            return $query->get();
        }
        
        return $query->where([
            ['from_date', '>=', $fromDate],
            ['to_date', '<=', $toDate]
        ]);

    }
}
