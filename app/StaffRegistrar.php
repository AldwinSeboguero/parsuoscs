<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffRegistrar extends Model
{
    protected $table='staff_registrars';
    protected $fillable = ['user_id','program_id'];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Program','program_id');
    }
}
