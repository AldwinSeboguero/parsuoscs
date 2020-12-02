<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceRequest extends Model
{
    protected $table='clearance_requests';   
    protected $fillable = [
        'token',
        'staff_id',
        'status',
        'student_id',
        'purpose_id',
        'created_at',
        'updated_at',
        'request_at',

    ] ;
 
    public function student()
    {
        return $this->belongsTo('App\Student','student_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Staff','staff_id');
    }
    public function purpose()
    {
        return $this->belongsTo('App\Purpose','purpose_id');
    }
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
    protected $dates = ['request_at','approved_at'];
}
