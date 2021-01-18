<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentPurposeSetup extends Model
{
    protected $table='student_purpose_setup'; 
    protected $fillable=['purpose_id'];   
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function purpose()
    {
        return $this->belongsTo('App\ClearancePurpose','purpose_id');
    }
}
