<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];
    const UPLOAD_DIR = 'uploads/files';

    public function jobs()
    {
        return $this->belongsToMany(Job::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
