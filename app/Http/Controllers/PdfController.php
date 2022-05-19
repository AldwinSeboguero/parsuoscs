<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Student;
use App\Clearance;
use Illuminate\Support\Carbon;
use App\ClearancePurpose;
use App\Campus;
use App\Program;
use App\Section; 
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
class PdfController extends Controller
{
    public function create(Request $request)
    {   
       
        $paperSize = 'a4';
        $orientation = 'portrait';
        $clearance = Clearance::where('id',$request->clearance)->first();
        // dd($clearance);
        $student = Student::find($clearance->student_id);
        $purposeClearance = ClearancePurpose::where('id',$clearance->purpose_id)->first();
        $purpose = json_decode($purposeClearance->purpose)->name.' '.
            json_decode($purposeClearance->purpose)->description;
		/* When saved, the PDF file generated will have a name with the format */
        /* 2010-john-doe-01012085945.pdf */
        
        $activeClearancePurpose = $purposeClearance;
  
        $clearanceRequest = ClearanceRequest::where('student_id',$student->id)->get();
        //signatories info
        $staffPD = Staff_PD::where('program_id', $student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffDEAN = Staff_DEAN::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffSTCOUNCIL = StaffStCouncil::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffCASHIER = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffOSAS = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffLIBRARY = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRAR = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRARSTAFF = StaffRegistrar::with('user')->where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first() ? 
        StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first()
        :
        Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();;

        //staff
        $staffPD_id =null;
        $staffDEAN_id =null;
        $staffSTCOUNCIL_id =null;
        $staffCASHIER_id =null;
        $staffOSAS_id =null;
        $staffLIBRARY_id =null;
        $staffREGISTRAR_id =null;
        $staffREGISTRARSTAFF_id =null;

        //clearance request signatory
        $pd = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',1);
        })
        ->latest('id')->first();

        $dean = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',2);
        })
        ->latest('id')->first();
        $council = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',8);
        })
        ->latest('id')->first();

        $cashier = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',3);
        })
        ->latest('id')->first();

        $osas = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',6);
        })
        ->latest('id')->first();

        $library = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',5);
        })
        ->latest('id')->first();

        $registrar = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',4);
        })
        ->latest('id')->first();
       
        $registrarstaff = ClearanceRequest::where('student_id',$student->id)
        ->where('approved_at','!=',null)
        ->where('purpose_id',$clearance->purpose_id)
        ->whereHas('staff',function($query){
            $query->where('designee_id',9);
        })
        ->latest('id')->first();

        // dd($pd);
         //staff id
         if ($staffPD) {
            $staffPD_id = $pd->staff_id;
         
         }
        
       
         if ($staffDEAN) {
            $staffDEAN_id = $dean->staff_id;
        }
        if ($staffSTCOUNCIL) {
            $staffSTCOUNCIL_id = $council->staff_id;
        }
        if ($staffCASHIER) {
            $staffCASHIER_id = $cashier->staff_id;
        }
        if ($staffOSAS) {
            $staffOSAS_id = $osas->staff_id;
        }
        if ($staffLIBRARY) {
            $staffLIBRARY_id = $library->staff_id;
        }
        if ($staffREGISTRAR && $registrar) {
            $staffREGISTRAR_id = $registrar->staff_id;
        } 
        if ($staffREGISTRARSTAFF) {
            
           
              
                //  $staf1 = $registrarstaff->staff_id;
                //  $staf2 = $registrar->staff_id;
                 $staffREGISTRARSTAFF_id = $registrarstaff ? $registrarstaff->staff_id : $registrar->staff_id;
                
        }
 
        $clearanceRequestSTCOUNCIL = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id',$staffSTCOUNCIL_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestCASHIER = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id', $staffCASHIER_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestLIBRARY = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id', $staffLIBRARY_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestOSAS = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id',$staffOSAS_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestPROGRAMDIRECTOR = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id',$staffPD_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestDEAN = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id',$staffDEAN_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRARSTAFF = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id', $staffREGISTRARSTAFF_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRAR = ClearanceRequest::orderBy('approved_at','desc')->where('approved_at','!=',null)->where('student_id',$student->id)
        ->where('staff_id',$staffREGISTRAR_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
 
        $clearanceREGS = $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF : $clearanceRequestREGISTRAR;




		$format = "mdyhis";
		$file_name = $student->slug . "-".
                    Carbon::now()->format($format) . ".pdf";
      
           $pdf = PDF::loadView('pdf',compact('student'
           , 'file_name'
           ,'purpose'
           ,'staffPD'
           ,'staffDEAN'
           ,'staffCASHIER'
           ,'staffSTCOUNCIL'
           ,'staffLIBRARY'
           ,'staffOSAS'
           ,'staffREGISTRARSTAFF'
           ,'clearanceRequestCASHIER'
           ,'clearanceRequestPROGRAMDIRECTOR'
           ,'clearanceRequestDEAN'
           ,'clearanceRequestSTCOUNCIL'
           ,'clearanceRequestLIBRARY'
           ,'clearanceRequestOSAS'
           ,'clearanceREGS'
           ,'clearance'
        ));
       
    	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
        // $pdf = PDF::loadView('pdf', $student);
  
        return $pdf->output();
    }
    public function createSGS(Request $request)
    {
        $paperSize = 'a4';
        $orientation = 'portrait';
        
        $clearance = Clearance::where('id',$request->clearance)->first();
        // dd($clearance);
        $student = Student::find($clearance->student_id);
        $purposeClearance = ClearancePurpose::where('id',$clearance->purpose_id)->first();
        $purpose = json_decode($purposeClearance->purpose)->name.' '.
            json_decode($purposeClearance->purpose)->description;
        $student = Student::find($clearance->student_id);
        
       
       
		/* When saved, the PDF file generated will have a name with the format */
        /* 2010-john-doe-01012085945.pdf */
        
        $activeClearancePurpose = $purposeClearance;
  
            $clearanceRequest = ClearanceRequest::where('student_id',$student->id)->get();
        //signatories info
        $staffPD = Staff_PD::where('program_id', $student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffDEAN = Staff_DEAN::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffSTCOUNCIL = StaffStCouncil::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffCASHIER = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffOSAS = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffLIBRARY = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRAR = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRARSTAFF = StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first() ? 
        StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first()
        :
        Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();;

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
         //clearance request signatory
         $pd = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',1);
         })
         ->latest('id')->first();
 
         $dean = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',2);
         })
         ->latest('id')->first();
         $council = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',8);
         })
         ->latest('id')->first();
 
         $cashier = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',3);
         })
         ->latest('id')->first();
 
         $osas = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',6);
         })
         ->latest('id')->first();
 
         $library = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',5);
         })
         ->latest('id')->first();
 
         $registrar = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',4);
         })
         ->latest('id')->first();
 
         $registrarstaff = ClearanceRequest::where('student_id',$student->id)
         ->where('approved_at','!=',null)
         ->where('purpose_id',$clearance->purpose_id)
         ->whereHas('staff',function($query){
             $query->where('designee_id',4);
         })
         ->latest('id')->first();
 
         // dd($pd);
          //staff id
          if ($staffPD) {
             $staffPD_id = $pd->staff_id;
          
          }
         
        
          if ($staffDEAN) {
             $staffDEAN_id = $dean->staff_id;
         }
         if ($staffSTCOUNCIL) {
             $staffSTCOUNCIL_id = $council->staff_id;
         }
         if ($staffCASHIER) {
             $staffCASHIER_id = $cashier->staff_id;
         }
         if ($staffOSAS) {
             $staffOSAS_id = $osas->staff_id;
         }
         if ($staffLIBRARY) {
             $staffLIBRARY_id = $library->staff_id;
         }
         if ($staffREGISTRAR) {
             $staffREGISTRAR_id = $registrar->staff_id;
         } 
         if ($staffREGISTRARSTAFF) {
            $staffREGISTRARSTAFF_id = Staff::where('designee_id',Designee::where('short_name','registrarstaff')->first()->id)->where('user_id',$staffREGISTRARSTAFF->user_id)->where('semester_id',$activeClearancePurpose->semester_id)->first()->id;
 
        }
        $clearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffSTCOUNCIL_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffCASHIER_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffLIBRARY_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffOSAS_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestPROGRAMDIRECTOR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffPD_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffDEAN_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffREGISTRARSTAFF_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffREGISTRAR_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
 
        $clearanceREGS = $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF : $clearanceRequestREGISTRAR;




		$format = "mdyhis";
		$file_name = $student->slug . "-".
                    Carbon::now()->format($format) . ".pdf";
      
           $pdf = PDF::loadView('sgspdf',compact('student'
           , 'file_name'
           ,'purpose'
           ,'staffPD'
           ,'staffDEAN'
           ,'staffCASHIER'
           ,'staffSTCOUNCIL'
           ,'staffLIBRARY'
           ,'staffOSAS'
           ,'staffREGISTRARSTAFF'
           ,'clearanceRequestCASHIER'
           ,'clearanceRequestPROGRAMDIRECTOR'
           ,'clearanceRequestDEAN'
           ,'clearanceRequestSTCOUNCIL'
           ,'clearanceRequestLIBRARY'
           ,'clearanceRequestOSAS'
           ,'clearanceREGS'
           ,'clearance'
        ));
       
    	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
        // $pdf = PDF::loadView('pdf', $student);
  
        return $pdf->output();
    }
    public function createLHS(Request $request)
    {
        $paperSize = 'a4';
        $orientation = 'portrait';
        $clearance = Clearance::where('id',$request->clearance)->first();
        $student = Student::find($clearance->student_id);
        $purposeClearance = ClearancePurpose::find($clearance->purpose_id);
        $purpose = json_decode($purposeClearance->purpose)->name.' '.
            json_decode($purposeClearance->purpose)->description;
		/* When saved, the PDF file generated will have a name with the format */
        /* 2010-john-doe-01012085945.pdf */
        
        $activeClearancePurpose = $purposeClearance;
  
            $clearanceRequest = ClearanceRequest::where('student_id',$student->id)->get();
        //signatories info
        $staffPD = Staff_PD::where('program_id', $student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffDEAN = Staff_DEAN::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffSTCOUNCIL = StaffStCouncil::where('college_id', $student->program->college_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffCASHIER = Staff::where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffOSAS = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffLIBRARY = Staff::where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRAR = Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();
        $staffREGISTRARSTAFF = StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first() ? 
        StaffRegistrar::where('program_id',$student->program_id)->where('semester_id',$activeClearancePurpose->semester_id)->first()
        :
        Staff::where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->semester_id)->first();;

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
            $staffPD_id = Staff::where('designee_id',Designee::where('short_name','pd')->first()->id)->where('user_id',$staffPD->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
         }
         if ($staffADVISER) {
            $staffADVISER_id = Staff::where('designee_id',Designee::where('short_name','pd')->first()->id)->where('user_id',$staffADVISER->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
         }
         if ($staffPRINCIPAL) {
            $staffPRINCIPAL_id = Staff::where('designee_id',Designee::where('name','Principal')->first()->id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
         if ($staffDEAN) {
            $staffDEAN_id = Staff::where('designee_id',Designee::where('short_name','dean')->first()->id)->where('user_id',$staffDEAN->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffSTCOUNCIL) {
            $staffSTCOUNCIL_id = Staff::where('designee_id',Designee::where('short_name','stcouncil')->first()->id)->where('user_id',$staffSTCOUNCIL->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffCASHIER) {
            $staffCASHIER_id = Staff::where('designee_id',Designee::where('short_name','cashier')->first()->id)->where('designee_id',Designee::where('name','Cashier')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffOSAS) {
            $staffOSAS_id = Staff::where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('designee_id',Designee::where('short_name','OSAS')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffLIBRARY) {
            $staffLIBRARY_id = Staff::where('designee_id',Designee::where('short_name','library')->first()->id)->where('designee_id',Designee::where('name','Library')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
         
        }
        if ($staffREGISTRAR) {
            $staffREGISTRAR_id = Staff::where('designee_id',Designee::where('short_name','registrar')->first()->id)->where('designee_id',Designee::where('name','Registrar')->first()->id)->where('campus_id',$student->program->campus_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
        
        }
        if ($staffREGISTRARSTAFF) {
            $staffREGISTRARSTAFF_id = Staff::where('designee_id',Designee::where('short_name','registrarstaff')->first()->id)->where('user_id',$staffREGISTRARSTAFF->user_id)->where('semester_id',$activeClearancePurpose->purpose->semester_id)->first()->id;
 
        }
         
 
        $clearanceRequestSTCOUNCIL = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffSTCOUNCIL_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestCASHIER = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffCASHIER_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestLIBRARY = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffLIBRARY_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestOSAS = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffOSAS_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestPROGRAMDIRECTOR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffPD_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestDEAN = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffDEAN_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRARSTAFF = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id', $staffREGISTRARSTAFF_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
        $clearanceRequestREGISTRAR = ClearanceRequest::where('student_id',$student->id)
        ->where('staff_id',$staffREGISTRAR_id)->where('purpose_id',$activeClearancePurpose->id)
        ->first();
 
        $clearanceREGS = $clearanceRequestREGISTRARSTAFF ? $clearanceRequestREGISTRARSTAFF : $clearanceRequestREGISTRAR;




		$format = "mdyhis";
		$file_name = $student->slug . "-".
                    Carbon::now()->format($format) . ".pdf";
      
           $pdf = PDF::loadView('lhspdf',compact('student'
           , 'file_name'
           ,'purpose'
           ,'staffPD'
           ,'staffDEAN'
           ,'staffCASHIER'
           ,'staffSTCOUNCIL'
           ,'staffLIBRARY'
           ,'staffOSAS'
           ,'staffREGISTRARSTAFF'
           ,'clearanceRequestCASHIER'
           ,'clearanceRequestPROGRAMDIRECTOR'
           ,'clearanceRequestDEAN'
           ,'clearanceRequestSTCOUNCIL'
           ,'clearanceRequestLIBRARY'
           ,'clearanceRequestOSAS'
           ,'clearanceREGS'
           ,'clearance'
        ));
       
    	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
        // $pdf = PDF::loadView('pdf', $student);
  
        return $pdf->output();
    }
}