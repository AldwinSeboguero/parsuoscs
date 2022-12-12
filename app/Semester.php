<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table='semesters';
    protected $fillable=['id','code','semester','from','to'];

    public function signatories(){
        return $this->hasMany('App\SignatoryV2'::class,'semester_id','id');
 
    }
}
