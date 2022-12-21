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

        $programs = SignatoryV2::where('user_id', Auth::user()->id)->get('program_id');
        if(Auth::user()->hasRole("admin")){
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::orderByDesc('updated_at')
                ->when($semester_id , function($q) use($semester_id){
                    $q->whereHas('clearance.purpose', function($q) use($semester_id){
                        $q->where('semester_id', $semester_id);
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
                ->whereHas('clearance.program', function($q) use($programs){
                    $q->whereIn('id', $programs);
                })  
                
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }

    }
}
