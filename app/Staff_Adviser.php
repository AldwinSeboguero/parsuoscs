<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff_Adviser extends Model
{
    protected $table='staff_adviser';
    protected $fillable = [
        'user_id',
        'section_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function section()
    {
        return $this->belongsTo('App\Section','section_id');
    }
    
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
}
