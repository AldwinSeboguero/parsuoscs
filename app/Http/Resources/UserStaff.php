<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\SignatoryV2;
class UserStaff extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         return [
        'prefix' => 'SIG',
        'prefix_num' => 000000,
        'id' => $this->id,
        'name' => $this->name,  
        'campus' => $this->designee->campus->name,
        'semester' => $this->designee->semester->code,
        // 'designees' => $this->designees->groupBy(['semester_id','designee_id','purpose_id']),
        'designee_name' => $this->designee->designee->short_name.'-'.$this->designee->campus->short_name,
        // 'college' => $this->designee->college->short_name,
        // 'program' => $this->designee->program->short_name,
        'purpose' => $this->designee->purpose->purpose,
        // 'user_id' => $this->designee->user->id,   
        // 'semester_id' => $this->designee->semester->id,
        // 'campus_id' => $this->designee->campus->id,
        // 'designee_id' => $this->designee->designee->id,
    ];
    }
}
