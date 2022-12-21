<?php

namespace App\Http\Controllers\V2\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\SubmitClearance; 
use App\Clearance;
use App\ClearancePurpose;
use Illuminate\Support\Carbon;
use PDF;

class ActiveClearanceController extends Controller
{
    public function index(){

        $student = Student::where('user_id', Auth::user()->id)->first();
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $countApproved = 0;
        $signatories  = SignatoryV2::orderBy('order')
                        ->where('campus_id', $student->program->campus_id)
                        ->where('college_id', $student->program->college_id)
                        ->where('program_id', $student->program_id)
                        ->whereHas('purpose', function($query) use($activeClearancePurpose){
                            $query->when($activeClearancePurpose, function($inner) use($activeClearancePurpose){
                                $inner->where('purpose',json_decode($activeClearancePurpose->purpose->purpose)->name)
                                ->where('semester_id', $activeClearancePurpose->purpose->semester_id);
                            });
                        })
                        ->get()->map(function($inner) use($activeClearancePurpose,$student,$countApproved){
                            if($activeClearancePurpose){
                               $cr = ClearanceRequestV2::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('signatory_id',$inner->id)
                                                ->where('student_id',$student->id)
                                                ->get();
                                $deficiency = Deficiency::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('student_id',$student->id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('signatory_id',$inner->id)
                                                ->where('completed',false)
                                                ->get();
                                return [
                                    'signatory_id' => $inner->id,
                                    'designee' => $inner->name,
                                    'office' => $inner->designee->name,
                                    'status' => $cr->first() ? $cr->first()->status : 0,   
                                    'approved_at' => $cr->first() ? ($cr->first()->approved_at ? $cr->first()->approved_at->toFormattedDateString() :  '') : '',
                                    'requestCount' => $cr ? $cr->count() : 0 ,
                                    'deficiencyCount' => $deficiency ? $deficiency->count() : 0 ,
                                    'defieciencyList' => $deficiency ? $deficiency : null,
                                    'loadingBtn' => false,
                                    'designee_id' => $inner->designee_id,
                                ];
                            }
                            
                            
                        });
        foreach($signatories as $key => $signatory){
            // dd($signatory);
            if($signatory){
                if($signatory['status'] == 1){
                    $countApproved+=1;
                }
            }
            
        }
        $dateSubmitted = false;
        $clearance  = '';
        if($activeClearancePurpose ){
            $clearance = Clearance::where('purpose_id',$activeClearancePurpose->purpose_id)->get()->first();
            $dateSubmitted = SubmitClearance::where('clearance_id', Clearance::where('purpose_id',$activeClearancePurpose->purpose_id)->get()->first()->id)->get()->first();
        }
        return response()->json([
            // 'staff' => SignatoryV2::find(20720),
            // 'student' => $student,
            // 'now' => $activeClearancePurpose->purpose->semester->from >= now()->format('Y-m-d'),
            // 'now2' => $activeClearancePurpose->purpose->semester->to >= now()->format('Y-m-d') ,
            'isOpen' => $activeClearancePurpose ? 
            (json_decode($activeClearancePurpose->purpose->purpose)->name == "Enrollment" ?
                $activeClearancePurpose->purpose->semester->from <= now()->format('Y-m-d') && $activeClearancePurpose->purpose->semester->to >= now()->format('Y-m-d') 
                : true )
                : false,
            'purpose' =>$activeClearancePurpose ? json_decode($activeClearancePurpose->purpose->purpose)->name.' '.json_decode($activeClearancePurpose->purpose->purpose)->description : null,
            'signatories'  => $activeClearancePurpose ? $signatories : null,
            'countApproved' =>$countApproved,
            'countSignatory' => $signatories->count(),
            'isSubmitted' => $activeClearancePurpose ? ($dateSubmitted ? true : false) : false,
            'isPurposeSetup' => $activeClearancePurpose ? true : false,
            'dateSubmitted' => $dateSubmitted != false && $dateSubmitted->created_at ? $dateSubmitted->created_at->diffForHumans() : '',
            'clearance_id' => $clearance ? $clearance->id : '',
            'student_name' => $student->name,
        ]);
    }

    public function sendRequest(Request $request){
        $student = Student::where('user_id', Auth::user()->id)->first();
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        // dd($request);
        ClearanceRequestV2::updateOrCreate([
            'token' => time(),
            'signatory_id' => $request->signatory_id,
            'status' => 0,
            'student_id' => $student->id,
            'purpose_id' => $activeClearancePurpose->purpose_id,
            'designee_id' => $request->designee_id,
            'created_at' => now(),
            'updated_at' => now(),
            'requested_at' => now(),
        ]);
        $countApproved = 0;


        $signatories  = SignatoryV2::orderBy('order')
                       
                        ->where('campus_id', $student->program->campus_id)
                        
                        ->where('college_id', $student->program->college_id)
                        ->where('program_id', $student->program_id)
                        ->whereHas('purpose', function($query) use($activeClearancePurpose){
                            $query->when($activeClearancePurpose, function($inner) use($activeClearancePurpose){
                                $inner->where('purpose',json_decode($activeClearancePurpose->purpose->purpose)->name)
                                ->where('semester_id', $activeClearancePurpose->purpose->semester_id);
                            });
                        })
                        ->get()->map(function($inner) use($activeClearancePurpose,$student){
                            if($activeClearancePurpose){
                               $cr = ClearanceRequestV2::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('signatory_id',$inner->id)
                                                ->where('student_id',$student->id)
                                                ->get();
                                $deficiency = Deficiency::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('student_id',$student->id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('completed',false)
                                                ->get();
                                return [
                                    'signatory_id' => $inner->id,
                                    'designee' => $inner->name,
                                    'office' => $inner->designee->name,
                                    'status' => $cr->first() ? $cr->first()->status : 0,   
                                    'approved_at' => $cr->first() ? ($cr->first()->approved_at ? $cr->first()->approved_at->toFormattedDateString() :  '') : '',
                                    'requestCount' => $cr ? $cr->count() : 0 ,
                                    'deficiencyCount' => $deficiency ? $deficiency->count() : 0 ,
                                    'loadingBtn' => false,
                                    'designee_id' => $inner->designee_id,

                                ];
                            }
                            
                            
                        });

            foreach($signatories as $key => $signatory){
                // dd($signatory);
                if($signatory['status'] == 1){
                    $countApproved+=1;
                }
            }
        return response()->json([
            // 'staff' => SignatoryV2::find(20720),
            // 'student' => $student,
            'semester' =>$activeClearancePurpose ? $activeClearancePurpose->purpose->semester_id : null,
            'purpose' =>$activeClearancePurpose ? json_decode($activeClearancePurpose->purpose->purpose)->name : null,
            'signatories'  => $activeClearancePurpose ? $signatories : null,
            'countApproved' =>$countApproved,
            'countSignatory' => $signatories->count(),
        ]);
    }


    public function createPdf(Request $request)
    {   
       
        $paperSize = 'a4';
        $orientation = 'portrait';
        $clearance = Clearance::where('id',$request->clearance_id)->get()->first();
        // dd($clearance);
        $student = Student::find($clearance->student_id);
        $purposeClearance = ClearancePurpose::where('id',$clearance->purpose_id)->first();
       
		/* When saved, the PDF file generated will have a name with the format */
        /* 2010-john-doe-01012085945.pdf */
       
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $countApproved = 0;

        $purpose = $activeClearancePurpose ? json_decode($activeClearancePurpose->purpose->purpose)->name.' '.json_decode($activeClearancePurpose->purpose->purpose)->description : null;
		
        $signatories  = SignatoryV2::orderBy('order')
                        ->where('campus_id', $student->program->campus_id)
                        ->where('college_id', $student->program->college_id)
                        ->where('program_id', $student->program_id)
                        ->whereHas('purpose', function($query) use($activeClearancePurpose){
                            $query->when($activeClearancePurpose, function($inner) use($activeClearancePurpose){
                                $inner->where('purpose',json_decode($activeClearancePurpose->purpose->purpose)->name)
                                ->where('semester_id', $activeClearancePurpose->purpose->semester_id);
                            });
                        })
                        ->get()->map(function($inner) use($activeClearancePurpose,$student,$countApproved){
                            if($activeClearancePurpose){
                               $cr = ClearanceRequestV2::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('signatory_id',$inner->id)
                                                ->where('student_id',$student->id)
                                                ->get();
                                $deficiency = Deficiency::where('purpose_id',$activeClearancePurpose->purpose_id)
                                                ->where('student_id',$student->id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('completed',false)
                                                ->get();
                                return [
                                    'signatory_id' => $inner->id,
                                    'designee' => $inner->name,
                                    'office' => $inner->designee->name,
                                    'status' => $cr->first() ? $cr->first()->status : 0,   
                                    'clearanceRequest_id' => $cr->first() ? $cr->first()->token : '',   
                                    'approved_at' => $cr->first() ? ($cr->first()->approved_at ? $cr->first()->approved_at->toFormattedDateString() :  '') : '',
                                    'requestCount' => $cr ? $cr->count() : 0 ,
                                    'deficiencyCount' => $deficiency ? $deficiency->count() : 0 ,
                                    'defieciencyList' => $deficiency ? $deficiency : null,
                                    'loadingBtn' => false,
                                    'designee_id' => $inner->designee_id,
                                ];
                            }
                            
                            
                        });


        $format = "mdyhis";
		$file_name = $student->slug . "-".
                    $clearance->id . ".pdf";
      
           $pdf = PDF::loadView('pdf',compact('student'
           , 'file_name'
           ,'purpose'
           ,'signatories'
           ,'clearance'
           ,'purpose'
        ));
       
    	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
        // $pdf = PDF::loadView('pdf', $student);
  
        return $pdf->output();
    }

    public function signatoryCreatePdf(Request $request)
    {   
       
        $paperSize = 'a4';
        $orientation = 'portrait';
        $clearance = Clearance::where('id',$request->clearance_id)->get()->first();
        // dd($clearance);
        $student = Student::find($clearance->student_id);
        $purposeClearance = ClearancePurpose::where('id',$clearance->purpose_id)->first();
       
		/* When saved, the PDF file generated will have a name with the format */
        /* 2010-john-doe-01012085945.pdf */
       
        $activeClearancePurpose = StudentPurposeSetup::where('user_id',$student->user_id)->first();
        $countApproved = 0;

        $purpose = $activeClearancePurpose ? json_decode($purposeClearance->purpose)->name.' '.json_decode($purposeClearance->purpose)->description : null;
		// dd($purpose);
        $signatories  = SignatoryV2::orderBy('order')
                        ->where('campus_id', $student->program->campus_id)
                        ->where('college_id', $student->program->college_id)
                        ->where('program_id', $student->program_id)
                        ->whereHas('purpose', function($query) use($purposeClearance){
                            $query->when($purposeClearance, function($inner) use($purposeClearance){
                                $inner->where('purpose',json_decode($purposeClearance->purpose)->name)
                                ->where('semester_id', $purposeClearance->semester_id);
                            });
                        })
                        ->get()->map(function($inner) use($purposeClearance,$student,$countApproved){
                            if($purposeClearance){
                               $cr = ClearanceRequestV2::where('purpose_id',$purposeClearance->id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('signatory_id',$inner->id)
                                                ->where('student_id',$student->id)
                                                ->get();
                                $deficiency = Deficiency::where('purpose_id',$purposeClearance->id)
                                                ->where('student_id',$student->id)
                                                ->where('designee_id',$inner->designee_id)
                                                ->where('completed',false)
                                                ->get();
                                return [
                                    'signatory_id' => $inner->id,
                                    'designee' => $inner->name,
                                    'office' => $inner->designee->name,
                                    'status' => $cr->first() ? $cr->first()->status : 0,   
                                    'clearanceRequest_id' => $cr->first() ? $cr->first()->token : '',   
                                    'approved_at' => $cr->first() ? ($cr->first()->approved_at ? $cr->first()->approved_at->toFormattedDateString() :  '') : '',
                                    'requestCount' => $cr ? $cr->count() : 0 ,
                                    'deficiencyCount' => $deficiency ? $deficiency->count() : 0 ,
                                    'defieciencyList' => $deficiency ? $deficiency : null,
                                    'loadingBtn' => false,
                                    'designee_id' => $inner->designee_id,
                                ];
                            }
                            
                            
                        });


        $format = "mdyhis";
		$file_name = $student->slug . "-".
                    $clearance->id . ".pdf";
      
           $pdf = PDF::loadView('pdf',compact('student'
           , 'file_name'
           ,'purpose'
           ,'signatories'
           ,'clearance'
           ,'purpose'
        ));
       
    	// $data = ['title' => 'Laravel 7 Generate PDF From View Example Tutorial'];
        // $pdf = PDF::loadView('pdf', $student);
  
        return $pdf->output();
    }

    public function submitClearance(Request $request)
    {
        // dd($request);
        $clearance = Clearance::where('id',$request->clearance_id)->get()->first();
        $submitted = SubmitClearance::where('clearance_id',$clearance->id)->get()->first();
        if(!$submitted){
        // $staff = Staff::find($request->staff_id); 
        $submitClearance['clearance_id'] = $clearance->id;
        // $submitClearance['staff_id'] = $request->staff_id;
        $submittedClearance = SubmitClearance::updateOrCreate($submitClearance);
        return response()->json(['isSubmitted' => true,
        'dateSubmitted' => $submittedClearance ? $submittedClearance->created_at->diffForHumans() : '',

        'message'=> "Succefully Submitted!"],200);
         }
         else {
            return response()->json(['message'=> "Already Exist!"],500);
         }
    }
    
}
