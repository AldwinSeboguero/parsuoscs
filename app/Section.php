<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table='sections';
    protected $fillable=['program_id','name'];
    public function program()
    {
        return $this->belongsTo('App\Program','program_id');
    }
}
