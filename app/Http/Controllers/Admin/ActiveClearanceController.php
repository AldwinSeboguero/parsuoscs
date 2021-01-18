<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Student;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\StudentCollection;
use App\Campus;
use App\Program;
use App\Section;
use App\Clearance;
use App\ClearanceRequest;
use App\Staff;
use App\Designee;
use App\SemesterSetup;
use App\StudentPurposeSetup;
use App\Staff_Dean;
use App\Staff_PD;
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Staff_Adviser;
use App\Deficiency;
use App\SubmitClearance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
class ActiveClearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendRequest(Request $request){
        $student =  Student::where('user_id', Auth::user()->id)->withCount('deficiencies')->first();
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
       
        if(!ClearanceRequest::where('student_id',$student->id)->where('staff_id',$request->staff_id)->where('purpose_id',$activeClearancePurpose->purpose_id)->first()){
            $clearanceRequest1 = new ClearanceRequest([
                'token' => time(),
                'staff_id' => $request->staff_id,
                'status' => 0,
                'student_id' => $student->id,
                'purpose_id' => $activeClearancePurpose->purpose_id,
                'created_at' => now(),
                'update_at' => now(),
                'request_at' => now(),

            ]); 
            $clearanceRequest1->save(); 
            $clearance = Clearance::where('student_id', $student->id)
            ->where('purpose_id',
            StudentPurposeSetup::where('user_id',Auth::user()->id)->first()->purpose_id
            )
            ->first();
            $clearanceRequest = ClearanceRequest::where('student_id',$student->id)->get();
        //signatories info
        $staffPD = Staff_PD::where('program_id', $student->program_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffDEAN = Staff_DEAN::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffSTCOUNCIL = StaffStCouncil::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffCASHIER = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffOSAS = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffLIBRARY = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffREGISTRAR = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffREGISTRARSTAFF = StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();

        //staff
        $staffPD_id =null;
        $staffDEAN_id =null;
        $staffSTCOUNCIL_id =null;
        $staffCASHIER_id =null;
        $staffOSAS_id =null;
        $staffLIBRARY_id =null;
        $staffREGISTRAR_id =null;
        $staffREGISTRARSTAFF_id =null;
         //staff id
         if ($staffPD) {
            $staffPD_id = Staff::where('user_id',$staffPD->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
         }
         if ($staffDEAN) {
            $staffDEAN_id = Staff::where('user_id',$staffDEAN->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffSTCOUNCIL) {
            $staffSTCOUNCIL_id = Staff::where('user_id',$staffSTCOUNCIL->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffCASHIER) {
            $staffCASHIER_id = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffOSAS) {
            $staffOSAS_id = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffLIBRARY) {
            $staffLIBRARY_id = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffREGISTRAR) {
            $staffREGISTRAR_id = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffREGISTRARSTAFF) {
            $staffREGISTRARSTAFF_id = Staff::where('user_id',$staffREGISTRARSTAFF->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
 
        }
         
 
        $clearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffSTCOUNCIL_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffCASHIER_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffLIBRARY_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffOSAS_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestPROGRAMDIRECTOR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffPD_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffDEAN_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffREGISTRARSTAFF_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffREGISTRAR_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();

        


        //deficiency count
        $deficiencyPD = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffPD_id) 
        ->where('completed',0)->get(); 
        $deficiencyDEAN = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffDEAN_id) 
        ->where('completed',0)->get();
        $deficiencySTCOUNCIL = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffSTCOUNCIL_id) 
        ->where('completed',0)->get();
        $deficiencyCASHIER = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffCASHIER_id) 
        ->where('completed',0)->get();
        $deficiencyOSAS = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffOSAS_id) 
        ->where('completed',0)->get();
        $deficiencyLIBRARY = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffLIBRARY_id) 
        ->where('completed',0)->get();
        $deficiencyREGISTRAR = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffREGISTRAR_id) 
        ->where('completed',0)->get();
        $deficiencyREGISTRARSTAFF = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffREGISTRARSTAFF_id ? $staffREGISTRARSTAFF_id : $staffREGISTRAR_id) 
        ->where('completed',0)->get();

        // CLearance request Count
        $countClearanceRequestPD = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffPD ? $staffPD->user_id : null)->first() ? 
        Staff::where('user_id',$staffPD ? $staffPD->user_id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffDEAN ? $staffDEAN->user_id : null)->first() ? 
        Staff::where('user_id',$staffDEAN ? $staffDEAN->user_id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffSTCOUNCIL ? $staffSTCOUNCIL->user->id : null)->first() ?
        Staff::where('user_id',$staffSTCOUNCIL ? $staffSTCOUNCIL->user->id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffCASHIER ? $staffCASHIER->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffOSAS ? $staffOSAS->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffLIBRARY ? $staffLIBRARY->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffREGISTRAR ? $staffREGISTRAR->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)->where('staff_id',
        $staffREGISTRARSTAFF ? Staff::where('user_id',$staffREGISTRARSTAFF ? $staffREGISTRARSTAFF->user_id : null)->first()->id : 0
        )
        ->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();



        //Active CLearance Purpose
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $submittedClearance = SubmitClearance::whereClearance_id($clearance->id)->first();
        return response()->json([
        'student' => $student,
        'activeClearancePurpose' => json_decode(json_decode($activeClearancePurpose->purpose)->purpose)->name." ".
        json_decode(json_decode($activeClearancePurpose->purpose)->purpose)->description,
        'clearance' => $clearance,
        'clearanceRequests' => $clearanceRequest,
        'approvedDateclearanceRequestSTCOUNCIL' => $clearanceRequestSTCOUNCIL ? $clearanceRequestSTCOUNCIL->approved_at ? $clearanceRequestSTCOUNCIL->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestCASHIER' => $clearanceRequestCASHIER ? $clearanceRequestCASHIER->approved_at ? $clearanceRequestCASHIER->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestOSAS' => $clearanceRequestOSAS ? $clearanceRequestOSAS->approved_at ? $clearanceRequestOSAS->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestLIBRARY' => $clearanceRequestLIBRARY ? $clearanceRequestLIBRARY->approved_at ? $clearanceRequestLIBRARY->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestPROGRAMDIRECTOR' => $clearanceRequestPROGRAMDIRECTOR ? $clearanceRequestPROGRAMDIRECTOR->approved_at ? $clearanceRequestPROGRAMDIRECTOR->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestDEAN' => $clearanceRequestDEAN ? $clearanceRequestDEAN->approved_at ? $clearanceRequestDEAN->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestREGISTRARSTAFF' => $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF->approved_at ? $clearanceRequestREGISTRARSTAFF->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestREGISTRAR' => $clearanceRequestREGISTRAR ? $clearanceRequestREGISTRAR->approved_at ? $clearanceRequestREGISTRAR->approved_at->toDayDateTimeString() : null : null,
        'signatoryNameclearanceRequestSTCOUNCIL' => $staffSTCOUNCIL ? $staffSTCOUNCIL->user : null,
        'signatoryNameclearanceRequestCASHIER' => $staffCASHIER ? $staffCASHIER->user : null,
        'signatoryNameclearanceRequestOSAS' => $staffOSAS ? $staffOSAS->user : null,
        'signatoryNameclearanceRequestLIBRARY' => $staffLIBRARY ? $staffLIBRARY->user : null,
        'signatoryNameclearanceRequestPROGRAMDIRECTOR' => $staffPD ? $staffPD->user : null,
        'signatoryNameclearanceRequestDEAN' => $staffDEAN ?  $staffDEAN->user : null,
        'signatoryNameclearanceRequestREGISTRARSTAFF' => $staffREGISTRARSTAFF ? $staffREGISTRARSTAFF->user : $staffREGISTRAR->user,
        'staffIdSTCOUNCIL' => $staffSTCOUNCIL_id,
        'staffIdCASHIER' => $staffCASHIER_id,
        'staffIdOSAS' => $staffOSAS_id,
        'staffIdLIBRARY' => $staffLIBRARY_id,
        'staffIdPD' => $staffPD_id,
        'staffIdDEAN' => $staffDEAN_id,
        'staffIdREGISTRARSTAFF' => $staffREGISTRARSTAFF_id ? $staffREGISTRARSTAFF_id : $staffREGISTRAR_id,
        'countDeficiencyPD' => $deficiencyPD ? $deficiencyPD->count() : 0,
        'countDeficiencyDEAN' => $deficiencyDEAN ? $deficiencyDEAN->count() : 0,
        'countDeficiencyCASHIER' => $deficiencyCASHIER ? $deficiencyCASHIER->count() : 0,
        'countDeficiencyOSAS' => $deficiencyOSAS ? $deficiencyOSAS->count() : 0,
        'countDeficiencyLIBRARY' => $deficiencyLIBRARY ? $deficiencyLIBRARY->count() : 0,
        'countDeficiencySTCOUNCIL' => $deficiencySTCOUNCIL ? $deficiencySTCOUNCIL->count() : 0,
        'countDeficiencyREGISTRARSTAFF' => $deficiencyREGISTRARSTAFF ? $deficiencyREGISTRARSTAFF->count() : $deficiencyREGISTRAR->count(),
        'countClearanceRequestPD' => $clearanceRequestPROGRAMDIRECTOR,
        'countClearanceRequestCASHIER' => $countClearanceRequestCASHIER,
        'countClearanceRequestDEAN' => $clearanceRequestDEAN,
        'countClearanceRequestOSAS' => $countClearanceRequestOSAS,
        'countClearanceRequestLIBRARY' => $countClearanceRequestLIBRARY,
        'countClearanceRequestSTCOUNCIL' => $clearanceRequestSTCOUNCIL,
        'countClearanceRequestREGISTRARSTAFF' => $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF : $clearanceRequestREGISTRAR ,
        'deficiencyPD' => $deficiencyPD,
        'deficiencyDEAN' => $deficiencyDEAN,
        'deficiencyCASHIER' => $deficiencyCASHIER,
        'deficiencyOSAS' => $deficiencyOSAS,
        'deficiencyLIBRARY' => $deficiencyLIBRARY,
        'deficiencySTCOUNCIL' => $deficiencySTCOUNCIL ? $deficiencySTCOUNCIL->count() : 0,
        'deficiencyREGISTRARSTAFF' => $deficiencyREGISTRARSTAFF ? $deficiencyREGISTRARSTAFF : $deficiencyREGISTRAR,
        'submittedClearance' => $submittedClearance,
       'asa' => $staffREGISTRARSTAFF
    ],200);

        }
        else{
            return response()->json(['message'=> "Duplicate Request!"],500);
        }
    }
    public function activeClearance(Request $request)
    {
        $student =  Student::where('user_id', Auth::user()->id)->withCount('deficiencies')->first();

        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $clearance = Clearance::where('student_id', $student->id)
        ->where('purpose_id',
        StudentPurposeSetup::where('user_id',Auth::user()->id)->first()->purpose_id
        )
        ->first();
        $clearanceRequest = ClearanceRequest::where('student_id',$student->id)->get();
        //signatories info
        $staffPD = Staff_PD::where('program_id', $student->program_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffDEAN = Staff_DEAN::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffSTCOUNCIL = StaffStCouncil::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffCASHIER = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffOSAS = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffLIBRARY = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffREGISTRAR = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();
        $staffREGISTRARSTAFF = StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first();

        //staff
        $staffPD_id =null;
        $staffDEAN_id =null;
        $staffSTCOUNCIL_id =null;
        $staffCASHIER_id =null;
        $staffOSAS_id =null;
        $staffLIBRARY_id =null;
        $staffREGISTRAR_id =null;
        $staffREGISTRARSTAFF_id =null;
         //staff id
         if ($staffPD) {
            $staffPD_id = Staff::where('user_id',$staffPD->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
         }
         if ($staffDEAN) {
            $staffDEAN_id = Staff::where('user_id',$staffDEAN->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffSTCOUNCIL) {
            $staffSTCOUNCIL_id = Staff::where('user_id',$staffSTCOUNCIL->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffCASHIER) {
            $staffCASHIER_id = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffOSAS) {
            $staffOSAS_id = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffLIBRARY) {
            $staffLIBRARY_id = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffREGISTRAR) {
            $staffREGISTRAR_id = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffREGISTRARSTAFF) {
            $staffREGISTRARSTAFF_id = Staff::where('user_id',$staffREGISTRARSTAFF->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
 
        }
         
 
        $clearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffSTCOUNCIL_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffCASHIER_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffLIBRARY_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffOSAS_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestPROGRAMDIRECTOR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffPD_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffDEAN_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffREGISTRARSTAFF_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();
        $clearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffREGISTRAR_id)->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->first();

        


        //deficiency count
        $deficiencyPD = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffPD_id) 
        ->where('completed',0)->get(); 
        $deficiencyDEAN = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffDEAN_id) 
        ->where('completed',0)->get();
        $deficiencySTCOUNCIL = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffSTCOUNCIL_id) 
        ->where('completed',0)->get();
        $deficiencyCASHIER = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffCASHIER_id) 
        ->where('completed',0)->get();
        $deficiencyOSAS = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffOSAS_id) 
        ->where('completed',0)->get();
        $deficiencyLIBRARY = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffLIBRARY_id) 
        ->where('completed',0)->get();
        $deficiencyREGISTRAR = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffREGISTRAR_id) 
        ->where('completed',0)->get();
        $deficiencyREGISTRARSTAFF = Deficiency::where('student_id',$student->id)
        ->where('purpose_id',$activeClearancePurpose->purpose_id)
        ->where('staff_id',$staffREGISTRARSTAFF_id ? $staffREGISTRARSTAFF_id : $staffREGISTRAR_id) 
        ->where('completed',0)->get();

        // CLearance request Count
        $countClearanceRequestPD = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffPD ? $staffPD->user_id : null)->first() ? 
        Staff::where('user_id',$staffPD ? $staffPD->user_id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffDEAN ? $staffDEAN->user_id : null)->first() ? 
        Staff::where('user_id',$staffDEAN ? $staffDEAN->user_id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)->where('staff_id',Staff::where('user_id',$staffSTCOUNCIL ? $staffSTCOUNCIL->user->id : null)->first() ?
        Staff::where('user_id',$staffSTCOUNCIL ? $staffSTCOUNCIL->user->id : null)->first()->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffCASHIER ? $staffCASHIER->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffOSAS ? $staffOSAS->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffLIBRARY ? $staffLIBRARY->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)->where('staff_id',$staffREGISTRAR ? $staffREGISTRAR->id : null)->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();
        $countClearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)->where('staff_id',
        $staffREGISTRARSTAFF ? Staff::where('user_id',$staffREGISTRARSTAFF ? $staffREGISTRARSTAFF->user_id : null)->first()->id : 0
        )
        ->whereIn('status',[0,2])->where('purpose_id',$activeClearancePurpose->purpose_id)->get()->count();



        //Active CLearance Purpose
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $submittedClearance = SubmitClearance::whereClearance_id($clearance->id)->first();
        return response()->json([
        'student' => $student,
        'activeClearancePurpose' => json_decode(json_decode($activeClearancePurpose->purpose)->purpose)->name." ".
        json_decode(json_decode($activeClearancePurpose->purpose)->purpose)->description,
        'clearance' => $clearance,
        'clearanceRequests' => $clearanceRequest,
        'approvedDateclearanceRequestSTCOUNCIL' => $clearanceRequestSTCOUNCIL ? $clearanceRequestSTCOUNCIL->approved_at ? $clearanceRequestSTCOUNCIL->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestCASHIER' => $clearanceRequestCASHIER ? $clearanceRequestCASHIER->approved_at ? $clearanceRequestCASHIER->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestOSAS' => $clearanceRequestOSAS ? $clearanceRequestOSAS->approved_at ? $clearanceRequestOSAS->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestLIBRARY' => $clearanceRequestLIBRARY ? $clearanceRequestLIBRARY->approved_at ? $clearanceRequestLIBRARY->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestPROGRAMDIRECTOR' => $clearanceRequestPROGRAMDIRECTOR ? $clearanceRequestPROGRAMDIRECTOR->approved_at ? $clearanceRequestPROGRAMDIRECTOR->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestDEAN' => $clearanceRequestDEAN ? $clearanceRequestDEAN->approved_at ? $clearanceRequestDEAN->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestREGISTRARSTAFF' => $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF->approved_at ? $clearanceRequestREGISTRARSTAFF->approved_at->toDayDateTimeString() : null : null,
        'approvedDateclearanceRequestREGISTRAR' => $clearanceRequestREGISTRAR ? $clearanceRequestREGISTRAR->approved_at ? $clearanceRequestREGISTRAR->approved_at->toDayDateTimeString() : null : null,
        'signatoryNameclearanceRequestSTCOUNCIL' => $staffSTCOUNCIL ? $staffSTCOUNCIL->user : null,
        'signatoryNameclearanceRequestCASHIER' => $staffCASHIER ? $staffCASHIER->user : null,
        'signatoryNameclearanceRequestOSAS' => $staffOSAS ? $staffOSAS->user : null,
        'signatoryNameclearanceRequestLIBRARY' => $staffLIBRARY ? $staffLIBRARY->user : null,
        'signatoryNameclearanceRequestPROGRAMDIRECTOR' => $staffPD ? $staffPD->user : null,
        'signatoryNameclearanceRequestDEAN' => $staffDEAN ?  $staffDEAN->user : null,
        'signatoryNameclearanceRequestREGISTRARSTAFF' => $staffREGISTRARSTAFF ? $staffREGISTRARSTAFF->user : $staffREGISTRAR->user,
        'staffIdSTCOUNCIL' => $staffSTCOUNCIL_id,
        'staffIdCASHIER' => $staffCASHIER_id,
        'staffIdOSAS' => $staffOSAS_id,
        'staffIdLIBRARY' => $staffLIBRARY_id,
        'staffIdPD' => $staffPD_id,
        'staffIdDEAN' => $staffDEAN_id,
        'staffIdREGISTRARSTAFF' => $staffREGISTRARSTAFF_id ? $staffREGISTRARSTAFF_id : $staffREGISTRAR_id,
        'countDeficiencyPD' => $deficiencyPD ? $deficiencyPD->count() : 0,
        'countDeficiencyDEAN' => $deficiencyDEAN ? $deficiencyDEAN->count() : 0,
        'countDeficiencyCASHIER' => $deficiencyCASHIER ? $deficiencyCASHIER->count() : 0,
        'countDeficiencyOSAS' => $deficiencyOSAS ? $deficiencyOSAS->count() : 0,
        'countDeficiencyLIBRARY' => $deficiencyLIBRARY ? $deficiencyLIBRARY->count() : 0,
        'countDeficiencySTCOUNCIL' => $deficiencySTCOUNCIL ? $deficiencySTCOUNCIL->count() : 0,
        'countDeficiencyREGISTRARSTAFF' => $deficiencyREGISTRARSTAFF ? $deficiencyREGISTRARSTAFF->count() : $deficiencyREGISTRAR->count(),
        'countClearanceRequestPD' => $clearanceRequestPROGRAMDIRECTOR,
        'countClearanceRequestCASHIER' => $countClearanceRequestCASHIER,
        'countClearanceRequestDEAN' => $clearanceRequestDEAN,
        'countClearanceRequestOSAS' => $countClearanceRequestOSAS,
        'countClearanceRequestLIBRARY' => $countClearanceRequestLIBRARY,
        'countClearanceRequestSTCOUNCIL' => $clearanceRequestSTCOUNCIL,
        'countClearanceRequestREGISTRARSTAFF' => $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF : $clearanceRequestREGISTRAR ,
        'deficiencyPD' => $deficiencyPD,
        'deficiencyDEAN' => $deficiencyDEAN,
        'deficiencyCASHIER' => $deficiencyCASHIER,
        'deficiencyOSAS' => $deficiencyOSAS,
        'deficiencyLIBRARY' => $deficiencyLIBRARY,
        'deficiencySTCOUNCIL' => $deficiencySTCOUNCIL,
        'deficiencyREGISTRARSTAFF' => $deficiencyREGISTRARSTAFF ? $deficiencyREGISTRARSTAFF : $deficiencyREGISTRAR,
        'submittedClearance' => $submittedClearance,
       'asa' => $staffREGISTRARSTAFF
    ],200);
    }
    
    public function submitClearance(Request $request)
    {
        $clearance = Clearance::find($request->clearance_id);
        $submitted = SubmitClearance::where('clearance_id',$clearance->id)->first();
        if(!$submitted){
        $staff = Staff::find($request->staff_id); 
        $submitClearance['clearance_id'] = $clearance->id;
        $submitClearance['staff_id'] = $request->staff_id;
        $submitClearance['created_at'] =now();
        $submittedClearance = SubmitClearance::updateOrCreate($submitClearance);
        return response()->json(['submittedClearance' => $submittedClearance,
        'message'=> "Succefully Submitted!"],200);
         }
         else {
            return response()->json(['message'=> "Already Exist!"],500);
         }
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
