<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Staff extends JsonResource
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
            'prefix' => 'SIG',
            'prefix_num' => 000000,
            'id' => $this->id,
            'name' => $this->user->name,  
            'campus' => $this->campus->name,
            'semester' => $this->semester->code,
            'designee' => $this->designee->name,
            'designee_name' => $this->designee->short_name.($this->designee->short_name =='pd' ? '-'.$this->program->short_name.'-'.$this->campus->short_name : '').($this->designee->short_name =='dean' ? '-'.$this->program->short_name.'-'.$this->college->short_name : '').($this->designee->short_name !='dean' && $this->designee->short_name !='pd' ? '-'.$this->program->short_name.'-'.$this->campus->short_name : ''),
            'college' => $this->college->short_name,
            'program' => $this->program->short_name,
            'purpose' => $this->purpose->purpose,
            'user_id' => $this->user->id,   
            'semester_id' => $this->semester->id,
            'campus_id' => $this->campus->id,
            'designee_id' => $this->designee->id,
    ];
    }
}
