<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Program extends JsonResource
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
            'prefix' => 'P',
            'prefix_num' => 0000,
            'id' => $this->id,
            'description' => $this->name,  
            'code' => $this->short_name,  
            'college' => $this->college->short_name,
            'campus' => $this->campus->short_name,
            'college_id' => $this->college->id,
            'campus_id' => $this->campus->id,

    ];
    }
}
