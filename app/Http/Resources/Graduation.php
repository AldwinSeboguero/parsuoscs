<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Graduation extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'prefix' => 'G',
            'prefix_num' => 0000,
            'id' => $this->id,
            'description' => $this->graduation,  
            'from' => $this->from ? $this->from->format('M d, Y') : null,
            'to' => $this->to ? $this->to->format('M d, Y') : null,
            'schedule' => ($this->from ? $this->from->format('M d, Y') : null).' - '. ($this->to ? $this->to->format('M d, Y') : null),
            'schedules' => [($this->from ? $this->from->format('M d, Y') : null), ($this->to ? $this->to->format('M d, Y') : null)],

    ];
    }
}
