<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Deficiency extends JsonResource
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
            'clearance_id' => $this->id,  
            'name' => $this->student->name,
            'student_number' => $this->student->student_number,
            'program' => $this->student->program->name,
            'purpose' => $this->purpose->purpose, 
            'semester' => $this->semester->semester, 
            
        ];
    }
}
