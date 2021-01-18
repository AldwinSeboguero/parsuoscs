<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table='staffs';
    protected $fillable = [
        'user_id',
        'designee_id',
        'campus_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function college()
    {
        return $this->belongsTo('App\College','college_id');
    }
    public function designee()
    {
        return $this->belongsTo('App\Designee','designee_id');
    }
    public function campus()
    {
        return $this->belongsTo('App\Campus','campus_id');
    }
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
}
