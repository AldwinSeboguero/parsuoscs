<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\SubmitClearance;

class Clearance extends Model
{
    use Uuid;
    protected $keyType = 'string';
    public $incrementing = true;
    protected $guarded = [];
    protected $fillable = [
        'id',
        'student_id',
        'program_id',
        'purpose_id',
        'updated_at',
        'created_at'

    ] ;
    public function student(){
        return $this->belongsTo('App\Student','student_id');
    }
    public function purpose()
    {
           return $this->belongsTo('App\ClearancePurpose','purpose_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Program','program_id'); 
    }
    public function clearancerequest(){
        return $this->hasMany('App\ClearanceRequestV2'::class,'purpose_id','purpose_id')
		->whereHas('purpose',function($q){
			$q->where('semester_id',8);
		})->where('status',1);
 
    }
    // public function scopeSubmitted(){
    //     return SubmitClearance::where('clearance_id',$this->student_id)->get();
    //     // return $this->hasMany('App\SubmitClearance'::class,'clearance_id','id');
    // }
    // public function semester()
    // {
    //     return $this->belongsTo('App\Semester','semester_id');
    // }
}
