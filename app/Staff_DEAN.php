<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff_DEAN extends Model
{
    protected $table='staff_dean';
    protected $fillable = [
        'user_id',
        'college_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function college()
    {
        return $this->belongsTo('App\College','college_id');
    }
}
