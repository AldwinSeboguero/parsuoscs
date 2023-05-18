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
            'deficiency' => $this->title,  
            'note' => $this->note,
            'completed' => $this->completed,
            'staff' => $this->signatory? $this->signatory->user->name : '',
            // 'designee' => $this->designee->id, 
            'student_name' => $this->student ? $this->student->name : '', 
            'student_number' => $this->student ?  $this->student->student_number : '', 
        ];
    }
}
