<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClearanceRequestV2 extends Model
{
    protected $table='clearance_request_v2';
    protected $fillable = [
        'id',
        'token',
        'status',
        'student_id',
        'signatory_id',
        'designee_id',
        'purpose_id',
        'requested_at',
        'approved_at',
        'updated_at',
        'created_at'
    ];
    public function student()
    {
        return $this->belongsTo('App\Student','student_id');
    }
    public function signatory()
    {
        return $this->belongsTo('App\SignatoryV2','signatory_id');
    }
    public function designee()
    {
        return $this->belongsTo('App\Designee','designee_id');
    }
    public function purpose()
    {
        return $this->belongsTo('App\ClearancePurpose','purpose_id');
    }
    public function signatories()
    {
        return $this->belongsToMany('App\SignatoryV2', 'signatory_id')
            // ->withPivot('status')
            ->withTimestamps();
    }
    protected $dates = ['requested_at','approved_at'];

}
