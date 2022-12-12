<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Semester extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'prefix' => 'S',
            'prefix_num' => 0000,
            'id' => $this->id,
            'description' => $this->semester,  
            'code' => $this->code,  
            'from' => $this->from,
            'to' => $this->to,
            'schedule' => $this->from.' - '. $this->to,
            'schedules' => [$this->from, $this->to],

    ];
    }
}
