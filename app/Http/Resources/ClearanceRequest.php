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
            'program' => $this->student->program->short_name,
            'staff' => $this->signatory ? $this->signatory->name : '',
            // 'deficiencies' => $this->student,
            'approved_at' => $this->approved_at ? $this->approved_at->format('M d, Y g:i A') : null,
            'request_at' => $this->requested_at ? $this->requested_at->format('M d, Y g:i A') : null,
            'purpose' => json_decode(json_decode($this->purpose)->purpose)->name.' '.
            json_decode(json_decode($this->purpose)->purpose)->description, 
            
        ];
     
    }
}
