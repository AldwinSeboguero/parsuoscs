<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiasAccount extends JsonResource
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
            'name' => $this->student->name,
            'program' => $this->student->program->name,
            'user_id' => $this->user_id,
            'password' => $this->password,
            'created_at' => $this->created_at->toDayDateTimeString(), 
        ];
    }
}
