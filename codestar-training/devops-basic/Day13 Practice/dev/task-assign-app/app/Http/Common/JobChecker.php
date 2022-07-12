<?php 

namespace App\Http\Common;

use App\Models\TimeSheet;

class JobChecker 
{
    public static function isPastDueDeadline($fromDate, $toDate, $deadline)
    {
        return $fromDate->greaterThan($deadline) || $toDate->greaterThan($deadline);
    }

}