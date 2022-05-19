<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiasAccount extends Model
{
    protected $table='sias_accounts';   
    protected $fillable = [
        'user_id',
        'password',
        'student_id', 

    ] ;
 
    public function student()
    {
        return $this->belongsTo('App\Student','student_id');
    }
}
