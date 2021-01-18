<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemesterSetup extends Model
{
    
    protected $table='semester_setup';    
    protected $fillable=['user_id','semester_id'];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
}
