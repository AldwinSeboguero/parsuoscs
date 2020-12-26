<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearancePurpose extends Model
{
    protected $table='purpose_clearance';
    protected $fillable=['clearance_id','purpose'];
    public function clearance()
    {
        return $this->belongsTo('App\Clearance','clearance_id');
    } 
} 
