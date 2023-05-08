<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\SubmitClearance; 
use App\Role; 
use App\User; 
use App\Semester;
use Illuminate\Support\Facades\Hash;
use App\Staff_PD;
use Illuminate\Support\Facades\Auth; 
use App\Http\Resources\SubmittedClearance as SubmittedClearanceResource;
use App\Http\Resources\SubmittedClearanceCollection;
use App\Staff;
use App\SignatoryV2;
class SubmittedController extends Controller
{
    
    public function index(Request $request){
        $per_page =$request->per_page ? $request->per_page : 10; 
        $semester = $request->semester;
        $search = $request->search;

        $semester_id = $request->semester_id;

        $programs = SignatoryV2::where('user_id', Auth::user()->id)->distinct('campus_id')->get('campus_id');
        if(Auth::user()->hasRole("admin")){
            return response()->json([
                'file_name' => $semester ? Semester::when($semester , function($q) use($semester){
                    $q->where('id', $semester);
                    
                })->first()->semester.'_'.'Submitted Clearances'.'_'.time().'.csv' : 'Submitted Clearances'.'_'.time().'.csv',
                'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::orderByDesc('updated_at')
                ->when($semester , function($q) use($semester){
                    $q->whereHas('clearance.purpose', function($q) use($semester){
                        $q->where('semester_id', $semester);
                    });
                })
                ->when($request->search, function($inner) use($request){
                    $inner->whereHas('clearance.student', function($q) use ($request){
                        $q->where('name', 'ILIKE', '%' . $request->search . '%')
                        ->orWhere('student_number', 'ILIKE', '%' . $request->search . '%');
                    });
                })
                ->when($request->college, function($inner) use($request){
                    $inner->whereHas('clearance.student.program',function($q) use($request){
                        $q->where('college_id',$request->college);
                    });
                }) 
                ->when($request->program, function($inner) use($request){
                    $inner->whereHas('clearance.student',function($q) use($request){
                        $q->where('program_id',$request->program);
                    });
                }) 
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }
        else{
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::orderByDesc('updated_at')
                ->when($semester , function($q) use($semester){
                    $q->whereHas('clearance.purpose', function($q) use($semester){
                        $q->where('semester_id', $semester);
                    });
                })
                ->when($search , function($q) use($search){
                    $q->whereHas('clearance.student', function($q) use($search){
                    $q->where('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('student_number', 'ILIKE', '%' . $search . '%');
                    });
                })
                ->when(!Auth::user()->hasRole("admin"), function ($q){
                    
                        $semester = Semester::orderByDesc('id')->first();
                        $latest_signatory_ids = SignatoryV2::where('user_id', Auth::user()->id)
                            ->where('semester_id', $semester->id)
                            ->get('campus_id');
                        $q->whereHas('clearance.student.program',function($q) use($latest_signatory_ids){
                            $q->whereIn('campus_id',$latest_signatory_ids);
                        });
                   
                })
                ->when($request->college, function($inner) use($request){
                    $inner->whereHas('clearance.student.program',function($q) use($request){
                        $q->where('college_id',$request->college);
                    });
                }) 
                ->when($request->program, function($inner) use($request){
                    $inner->whereHas('clearance.student',function($q) use($request){
                        $q->where('program_id',$request->program);
                    });
                }) 
                // ->whereHas('clearance.program', function($q) use($programs){
                //     $q->whereIn('id', $programs);
                // }) 
                ->whereHas('clearance.student.program', function($q) use($programs){
                    $q->whereIn('campus_id', $programs);
                })  
                
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                'file_name' => $semester ? Semester::when($semester , function($q) use($semester){
                    $q->where('id', $semester);
                    
                })->first()->semester.'_'.'Submitted Clearances'.'_'.time().'.csv' : 'Submitted Clearances'.'_'.time().'.csv',
                ],200);
        }

    }
}
