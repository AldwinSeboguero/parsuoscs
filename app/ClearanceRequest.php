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
        'designee_id',
        'signatory_name',
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
        return $this->belongsTo('App\ClearancePurpose','purpose_id');
    }
    public function deficiencies()
    {
        return $this->hasManyThrough('App\ClearanceRequest','App\Deficiency','student_id','student_id');
    } 
    

    // public function semester()
    // {
    //     return $this->belongsTo('App\Semester','semester_id');
    // }
    protected $dates = ['request_at','approved_at'];
    
}
