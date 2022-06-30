<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deficiency extends Model
{
    
    protected $table='deficiencies';   
    protected $fillable =['title','note','staff_id','completed','designee_id','student_id','purpose_id','semester_id','signatory_id'];
    public function signatory()
    {
        return $this->belongsTo('App\SignatoryV2','signatory_id');
    }
    public function designee()
    {
        return $this->belongsTo('App\Designee','designee_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Staff','staff_id');
    }
    public function students()
    {
        return $this->belongsToMany('App\Student','student_id')->withTimestamps();
    }
    public function student()
    {
        return $this->belongsTo('App\Student','student_id');
    }
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
    public function purpose()
    {
        return $this->belongsTo('App\ClearancePurpose','purpose_id');
    } 
  
}
