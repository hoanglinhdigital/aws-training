<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffInfo extends Model
{
    use HasFactory;

    protected $table = 'staff_info';
    protected $guarded = [];

    public function staff() 
    {
        return $this->belongsTo(Staff::class);
    }
}
