<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentPurposeSetup extends Model
{
    protected $table='student_purpose_setup'; 
    protected $fillable=['purpose'];   
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
