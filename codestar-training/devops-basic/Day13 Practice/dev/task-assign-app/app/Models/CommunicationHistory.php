<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationHistory extends Model
{
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
