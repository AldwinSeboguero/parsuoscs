<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedClearance extends JsonResource
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
            'clearance_id' => $this->clearance_id,
            'name' => $this->clearance->student->name,
            'student_number' => $this->clearance->student->student_number,
            'program' => $this->clearance->student->program->name,
        ];
    }
}
