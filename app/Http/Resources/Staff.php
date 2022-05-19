<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Staff extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
        'id' => $this->id,
        'name' => $this->user->name,  
        'campus' => $this->campus->name,
        'semester' => $this->semester->semester,
        'user_id' => $this->user->id,   
        'semester_id' => $this->semester->id,
        'campus_id' => $this->campus->id,
        'designee_id' => $this->designee->id,
    ];
    }
}
