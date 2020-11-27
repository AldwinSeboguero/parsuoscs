<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClearanceRequest extends JsonResource
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
            'token' => $this->token,
            'name' => $this->student->name,
            'student_number' => $this->student->student_number,
            'program' => $this->student->program->name,
            'staff' => $this->staff->user->name,
            'purpose' => $this->created_at,
        ];
    }
}
