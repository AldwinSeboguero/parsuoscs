<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
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
            'name' => $this->name,
            'campus' => $this->program->campus->name,
            'program' => $this->program->name,
            'section' => $this->section->name,
            'year' => $this->year,
            'student_number' => $this->student_number,
            'code' => $this->initial_password,
            'created_at' => $this->created_at->toDayDateTimeString(), 
        ];
    }
}
