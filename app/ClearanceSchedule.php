<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceSchedule extends Model
{
    protected $table = 'clearance_schedules';
    
    protected $fillable = [
        'college_id',
        'from',
        'to',
        'semester_id',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    
}