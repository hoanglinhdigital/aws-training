<?php

namespace App\Http\Controllers;

use App\Models\UpdateJobHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UpdateJobHistoryController extends Controller
{
    public function index(Request $request)  
    {

        if ($request->has('job-id')) {
            $jobId = $request->input('job-id');
            $histories = UpdateJobHistory::where('job_id', $jobId)->get();
            
            $reformatedHistories = $this->reformat($histories);
            return response()->json($reformatedHistories);
        }
        else {
            return response(UpdateJobHistory::all());
        }
    }

    private function reformat($histories)
    {
        foreach ($histories as $history) {
            $fieldName = $history->field;
            $fieldName = __('jobStatus.' . $fieldName, [], 'vi');
            $history->field = $fieldName;
        }
        return $histories;
    }
    
}
