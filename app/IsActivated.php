<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsActivated extends Model
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
}
