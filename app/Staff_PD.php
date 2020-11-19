<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff_PD extends Model
{
    protected $table='staff_pd';
    protected $fillable = [
        'user_id',
        'program_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Program','program_id');
    }
    public function clearancerequest()
    {
        return $this->belongsToMany('App\ClearanceRequest','clearance_requests','student_id','staff_id')->withTimestamps();
    }
}
