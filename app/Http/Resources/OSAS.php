<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OSAS extends JsonResource
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
            'designee' => $this->designee->name, 
            'campus' => $this->campus->name,
            'semester' => $this->semester->semester,
        ];
    }
}
