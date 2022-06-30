<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
class Clearance extends Model
{
    use Uuid;
    protected $keyType = 'string';
    public $incrementing = true;
    protected $guarded = [];
    protected $fillable = [
        'student_id',
        'program_id',
        'purpose_id'

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
    // public function semester()
    // {
    //     return $this->belongsTo('App\Semester','semester_id');
    // }
}
