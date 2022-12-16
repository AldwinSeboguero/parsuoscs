<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role ? ($this->role->role ? ucfirst($this->role->role->name) : '') : '', 
            'role_id' => $this->role ? ($this->role->role ? $this->role->role->id : '') : '', 
            'created_at' => $this->created_at ? $this->created_at->toDayDateTimeString() : '', 
            'updated_at' => $this->updated_at ? $this->updated_at->toDayDateTimeString() : '', 
        ];
    }
}
