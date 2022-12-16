<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table='programs';
    protected $fillable=['id','name','short_name','college_id','campus_id'];
    public function users()
    {
        return $this->belongsToMany('App\User')
                    ->withTimestamps();
    }
    public function college()
    {
        return $this->belongsTo('App\College','college_id');
    }
    public function campus()
    {
        return $this->belongsTo('App\Campus','campus_id');
    }
    public function signatories(){
        return $this->hasMany('App\SignatoryV2','program_id','id');
    }
    public function signatory($designation){
        if($this->signatories()->whereIn('designation_id', $designation)->first()){
            return true;
        }

        return false;
    }
   
    
}
