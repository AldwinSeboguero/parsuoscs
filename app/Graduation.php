<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Graduation extends Model
{
    protected $table='graduations';
    protected $fillable=['graduation','from','to'];
    protected $dates = ['from','to'];

}
