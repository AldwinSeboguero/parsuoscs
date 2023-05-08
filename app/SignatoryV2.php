<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignatoryV2 extends Model
{
    protected $table='signatories_v2';
    protected $fillable = [
        'id',
        'program_id',
        'campus_id',
        'college_id',
        'user_id',
        'name',
        'designee_id',
        'semester_id',
        'order',
        'purpose_id',
        'updated_at',
        'created_at'
    ];
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function program()
    {
        return $this->belongsTo('App\Program','program_id');
    }
    public function campus()
    {
        return $this->belongsTo('App\Campus','campus_id');
    }public function college()
    {
        return $this->belongsTo('App\College','college_id');
    }public function designee()
    {
        return $this->belongsTo('App\Designee','designee_id');
    }
    public function semester()
    {
        return $this->belongsTo('App\Semester','semester_id');
    }
    public function purpose()
    {
        return $this->belongsTo('App\Purpose','purpose_id');
    }
    public function clearance_requests()
{
    return $this->hasMany(ClearanceRequestV2::class,'signatory_id');
   
}
}
