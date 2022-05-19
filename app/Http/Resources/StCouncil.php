<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StCouncil extends JsonResource
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
            'college' => $this->college->short_name, 
            'semester' => $this->semester->semester,
            'college_id' => $this->college->id, 
            'semester_id' => $this->semester->id,
            'user_id' => $this->user->id,
        ];
    }
}
