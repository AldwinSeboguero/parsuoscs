<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearancePurpose extends Model
{
    protected $table='purpose_clearance';
    protected $fillable=['id','student_id','purpose','semester_id'];
    public function clearance()
    {
        return $this->belongsTo('App\Student','student_id');    
    } 
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    } 
} 
