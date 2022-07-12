<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmountConfirm extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function confirmedBy()
    {
        return $this->belongsTo(Staff::class);
    }

    public function jobAssign()
    {
        return $this->belongsTo(JobAssign::class, 'job_assign_id');
    }
}
