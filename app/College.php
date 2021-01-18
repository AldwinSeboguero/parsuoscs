<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $table='colleges';
    protected $fillable=['name','short_name','campus_id'];
    public function campus()
    {
        return $this->belongsTo('App\Campus','campus_id');
    }
}
