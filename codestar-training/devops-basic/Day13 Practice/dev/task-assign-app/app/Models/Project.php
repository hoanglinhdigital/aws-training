<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get scope of jobs belongs to staff. Project belongs to a staff if:
     * - Current staff is the assigner of a job which belongs to the project
     * - Staff has been assigned as direct assignee or with forward process method of a job which belongs to the project
     *
     * @param QueryBuilder $query
     * @param Int $staffId
     * @return QueryBuilder
     */

    public function scopeBelongsToStaff($query, $staffId)
    {
        return $query->whereHas('jobs', fn ($subQuery) => 
            $subQuery->where('assigner_id', $staffId)
            ->orWhereHas('assignees', fn ($q) => $q->where('staff.id', $staffId))
        )
        ->orWhereHas('jobs.jobAssigns', fn ($subQuery) =>
            $subQuery->where([
                    'staff_id' => $staffId,
                    'status' => JobAssign::STATUS_ACTIVE
            ])
            ->whereHas('parent.job.project', fn ($q) => $q->where('id', $this->id))
        );

    }


}
