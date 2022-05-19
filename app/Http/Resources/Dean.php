<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Dean extends JsonResource
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
            'college' => $this->college->name, 
            
            'user_id' => $this->user->id,  
            'college_id' => $this->college->id, 
            'semester_id' => $this->semester->id,
            'semester' => $this->semester->semester,
        ];
    }
}
