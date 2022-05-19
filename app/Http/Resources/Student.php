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
            'program' => $this->program->short_name.'|'.$this->program->campus->short_name,
            'section' => $this->section->name,
            'campus_id' => $this->program->campus->id,
            'program_id' => $this->program->id,
            'section_id' => $this->section->id,
            'year' => $this->year,
            'student_number' => $this->student_number,
            'code' => $this->initial_password,
            'deficiencies' => $this->deficiencies,
            'created_at' => $this->created_at->toDayDateTimeString(), 
            'stat' => $this->user_id ? '1' : '0',
            'user_id' => $this->user_id,
        ];
    }
}
