<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\SignatoryV2;
use Illuminate\Support\Facades\Auth;
use App\Staff;
use App\Designee;
use App\Staff_PD;
use App\Student;
use App\Staff_DEAN;
use App\StaffStCouncil;
use App\StaffRegistrar;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Deficiency;
use App\AdminSetting;
use App\ClearanceRequestV2;
use App\ClearancePurpose;
use Illuminate\Support\Carbon;
use App\Purpose;
use App\PurposeClearance;
class Clearance extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    public $preserveKeys = true;

    public function toArray($request)
    {
        $student = Student::where('user_id', Auth::user()->id)->first();
        $signatoryCount = SignatoryV2::orderBy('order')
                ->where('campus_id', $student->program->campus_id)
                ->where('college_id', $student->program->college_id)
                ->where('program_id', $student->program_id)
                ->where('purpose_id', Purpose::where('purpose',json_decode($this->purpose->purpose)->name)->get()->first()->id)
                ->where('semester_id',$this->purpose->semester_id)
                ->get()->count();
                $cr = ClearanceRequestV2::where('purpose_id',$this->purpose_id)
                                                        ->where('student_id',$student->id)
                                                        ->where('status',true)
                                                        ->distinct('designee_id')
                                                        ->get()->count();
        return [
            // 'key' => $this->key,
            'purpose' => json_decode(json_decode($this->purpose)->purpose)->name.' '.
            json_decode(json_decode($this->purpose)->purpose)->description, 
            'program' => $this->program ? $this->program->name : '',
            'campus' =>$this->program ? $this->program->campus->name : '',
            'status' => $cr >= $signatoryCount,
            'signatoryCount' => $signatoryCount,
            'ass' =>json_decode($this->purpose->purpose)->name,
            'cr' =>$cr >= $signatoryCount,
            
        ];
    }
}
