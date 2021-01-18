<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Clearance extends JsonResource
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
            'stcouncil' => $this->student_council,
            'cashier' => $this->cashier,
            'library' => $this->library,
            'pd' => $this->program_director,
            'dean' => $this->college_deaan,
            'osas' => $this->osas,
            'registrar' => $this->registrar,
            'adviser' => $this->class_adviser,
            'principal' => $this->principal,
            'purpose' => json_decode(json_decode($this->purpose)->purpose)->name.' '.
            json_decode(json_decode($this->purpose)->purpose)->description, 
            
        ];
    }
}
