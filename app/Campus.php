<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table='campuses';
    protected $fillable=['name','short_name'];
}
