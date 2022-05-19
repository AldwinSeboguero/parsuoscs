<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
class ActiveClearanceController extends Controller
{
    public function index(){
        $student = Student::where('user_id', Auth::user()->id)->first();
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $signatory = Staff::orderByDesc('designee_id')->where('campus_id', $student->program->campus_id)->where('semester_id', $activeClearancePurpose->purpose->semester_id)->with('user')->get();
        $stadN ='';
        $stad = '1';
        if(AdminSetting::where('name',json_decode($activeClearancePurpose->purpose->purpose)->name)->first()){
            $stadN = AdminSetting::where('name',json_decode($activeClearancePurpose->purpose->purpose)->name)->first()->name;
        
        
        if($stadN == "Enrollment"){
            $stad = AdminSetting::where('name',json_decode($activeClearancePurpose->purpose->purpose)->name)->first()->value;
        }
        elseif ($stadN == "Graduation") {
            $stad = AdminSetting::where('name',json_decode($activeClearancePurpose->purpose->purpose)->name)->first()->value;
        }
        else{
            $stad = AdminSetting::where('name',json_decode($activeClearancePurpose->purpose->purpose)->name)->first()->value;   
        }
         }
        return response()->json([
            'stad' => $stad,
            'student' => $student,
            'purpose' => $activeClearancePurpose,
            'signatories' =>  $signatory->map(function($inner) use($student,$activeClearancePurpose){
                //Program Director
                if($inner->designee_id == 1){
                    $staff =Staff_PD::where('user_id', $inner->user_id)->where('semester_id', $activeClearancePurpose->purpose->semester_id)->where('program_id', $student->program_id)->get();
                 
                    if($staff->count() != 0){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'Program Director',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                        ];
                    }
                    else{
                        return false;
                    }
                    
                   
                }
                //college dean
                else if($inner->designee_id == 2){
                    $staff =Staff_DEAN::where('user_id', $inner->user_id)->where('semester_id', $activeClearancePurpose->purpose->semester_id)->where('college_id', $student->program->college_id)->get();
                 
                    if($staff->count() != 0){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'College Dean',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                        ];
                    }
                    else{
                        return false;
                    }
                    
                   
                }
                //osas
                else if($inner->designee_id == 6){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'OSAS',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                            
                        ]; 
                }
                //Cashier
                else if($inner->designee_id == 3){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'Cashier',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                            
                        ]; 
                }
                 //osas
                else if($inner->designee_id == 5){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'Library',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                            
                        ]; 
                }
                //student council
                else if($inner->designee_id == 8){
                    $staff =StaffStCouncil::where('user_id', $inner->user_id)->where('semester_id', $activeClearancePurpose->purpose->semester_id)->where('college_id', $student->program->college_id)->get();
                    // dd($staff_pd);
                    if($staff->count() != 0){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'Student Council',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                           
                        ];
                    }
                    else{
                        return false;
                    }
                    
                   
                }
                //registrar
                else if($inner->designee_id == 9){
                    $staff =StaffRegistrar::where('user_id', $inner->user_id)->where('semester_id', $activeClearancePurpose->purpose->semester_id)->where('program_id', $student->program_id)->get();
                    // dd($staff_pd);
                    if($staff->count() != 0){
                        return [
                            'designee_id' => $inner->designee_id,
                            'designee' => 'Registrar',
                            'staff_id' => $inner->id,
                            'user' => $inner->user->name,
                            'clearance_request' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first(),
                            'clearance_request_status' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->status :
                            0,
                            'date_approved' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first() ?
                            ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->latest('id')->first()->approved_at->toDayDateTimeString() :
                            null,
                            'deficiencies' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get(),
                            'deficiencyCount' => Deficiency::where('designee_id', $inner->designee_id)
                            ->where('purpose_id', $activeClearancePurpose->purpose_id)
                            ->where('student_id', $student->id)->get()->count(),
                            'clearanceRequestCount' => ClearanceRequest::with('staff.user')
                            ->whereIn('staff_id', Staff::where('user_id',$inner->user_id)->get('id'))
                            ->where('student_id',$student->id)
                            ->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count(),
                           
                        ];
                    }
                    else{
                        return false;
                    }
                    
                   
                }
                 //University Registrar 
                 else if($inner->designee_id == 4){
                    return false;
                }
                 //addviser 
                 else if($inner->designee_id == 11){
                    return false;
                }
                //addviser 
                else if($inner->designee_id == 12){
                    return false;
                }
                 //addviser 
                 else if($inner->designee_id == 13){
                    return false;
                }
                 //addviser 
                 else if($inner->designee_id == 14){
                    return false;
                }
                 //addviser 
                 else if($inner->designee_id == 15){
                    return false;
                }
                 //addviser 
                 else if($inner->designee_id == 16){
                    return false;
                }
                //principal 
                else if($inner->designee_id == 10){
                    return false;
                }

                else{
                    return false;
                }
            })->reject(function ($value) {
                return $value === false;
            })->values()
        ]);
       
    }
}
