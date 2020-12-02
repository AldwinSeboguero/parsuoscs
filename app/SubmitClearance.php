<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubmitClearance extends Model
{
    
    protected $table='submit_clearances';
    protected $fillable=['clearance_id','staff_id'];
    public function clearance()
    {
        return $this->belongsTo('App\Clearance','clearance_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Staff','staff_id');
    }
}
