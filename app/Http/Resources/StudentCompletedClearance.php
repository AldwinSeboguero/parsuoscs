<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\SubmitClearance;
class StudentCompletedClearance extends JsonResource
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
            'student_number' => $this->student->student_number,
            'name' => $this->student->name,
            'program' => $this->student->program->name,
            'college' => $this->student->program->college->name,
            'purpose' => json_decode(json_decode($this->purpose)->purpose)->name.' '.
            json_decode(json_decode($this->purpose)->purpose)->description,
            'requestCompleted' =>$this->clearancerequest->count() >= 4 ? 'Completed' : 'With Pending Request',
            'isSubmitted' => SubmitClearance::where('clearance_id',$this->id)->count() >=1 ? 'Submitted' : 'Pending for Submission',
        ];
    }
}
