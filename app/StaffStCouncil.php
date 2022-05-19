<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffStCouncil extends Model
{
    protected $table='staff_studentcouncil';
    protected $fillable = [
        'user_id',
        'college_id',
        'semester_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function college()
    {
        return $this->belongsTo('App\College','college_id');
    }
    
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
}
