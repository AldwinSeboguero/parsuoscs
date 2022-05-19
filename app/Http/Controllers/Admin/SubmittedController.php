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
class SubmittedController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $semester_id = $request->semester_id;
        
		if(Auth::user()->hasRole("admin")){
			 if ($semester_id) {
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
                
                ->with('clearance.student')  
                ->with('clearance.student.program')  
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }
        
        else{
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
                
                ->with('clearance.student')  
                ->with('clearance.student.program')   
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }
		}
        else if(Auth::user()->hasRole("pd")){
            if ($semester_id) {
           return response()->json([
               'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
               ->whereHas('clearance.student', function($query){
                   $query->whereIn('program_id', Staff_PD::where('user_id',Auth::user()->id)->get('program_id'));
               })
               ->whereHas('clearance.purpose', function($q) use($semester_id){
                $q->where('semester_id', $semester_id);
            })  
               ->with('clearance.student')  
               ->with('clearance.student.program')  
               ->paginate($per_page)) ,
               'semesters' => Semester::orderByDesc('id')->get(),
               ],200);
       }
       else{
        return response()->json([
            'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
            ->whereHas('clearance.student', function($query){
                $query->whereIn('program_id', Staff_PD::where('user_id',Auth::user()->id)->get('program_id'));
            })
            
            ->with('clearance.student')  
            ->with('clearance.student.program')   
            ->paginate($per_page)) ,
            'semesters' => Semester::orderByDesc('id')->get(),
            ],200);
    }
    }

		else{
			
        $campus = Staff::where('user_id', Auth::user()->id)->first()->campus->id;
        if ($semester_id) {
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
                
                ->with('clearance.student')  
                ->with('clearance.student.program') 
                ->whereHas('clearance.student.program', function($q) use($campus){
                    $q->where('campus_id', $campus);
                })   
                ->whereHas('clearance.purpose', function($q) use($semester_id){
                    $q->where('semester_id', $semester_id);
                })  
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }
        else{
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::orderByDesc('updated_at')->with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')
                
                ->with('clearance.student')  
                ->with('clearance.student.program') 
                ->whereHas('clearance.student.program', function($q) use($campus){
                    $q->where('campus_id', $campus);
                })  
                ->paginate($per_page)) ,
                'semesters' => Semester::orderByDesc('id')->get(),
                ],200);
        }
		}
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
           
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->id;
        $per_page = $request->per_page;
        $semester_id = $request->semester_id;
		if(Auth::user()->hasRole("admin")){
			if ($semester_id ) {
        return response()->json([
            'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::orderByDesc('updated_at')->with('clearance')
                ->with('clearance.purpose') 
                
                ->with('clearance.student')  
                ->with('clearance.student.program')  
                ->whereHas('clearance.student', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
                ->paginate($per_page))  
            ],200);
        }
        else{
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(
                    SubmitClearance::orderByDesc('updated_at')->with('clearance')
                    ->with('clearance.purpose')
                    ->with('clearance.student')   
                    ->with('clearance.student')  
                    ->with('clearance.student.program') 
                   
                    ->whereHas('clearance.student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    }) 
                    ->orWhereHas('clearance.student', function($q) use ($id){
                        $q->where('student_number', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('clearance.student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('clearance.purpose', function($q) use ($id){
                        $q->where('purpose', 'ILIKE', '%' . $id . '%');
                    }) 
                    ->paginate($per_page))  
                ],200);
        }
		}
		else{
			
        $campus = Staff::where('user_id', Auth::user()->id)->first()->campus->id;
        if ($semester_id ) {
        return response()->json([
            'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::orderByDesc('updated_at')->with('clearance')
                ->with('clearance.purpose') 
                
                ->with('clearance.student')  
                ->with('clearance.student.program') 
                ->whereHas('clearance.purpose', function($q) use($semester_id){
                    $q->where('semester_id', $semester_id);
                }) 
                ->whereHas('clearance.student', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
                ->whereHas('clearance.student.program', function($q) use($campus){
                    $q->where('campus_id', $campus);
                })  
                ->paginate($per_page))  
            ],200);
        }
        else{
            return response()->json([
                'submittedclearances' => new SubmittedClearanceCollection(
                    SubmitClearance::orderByDesc('updated_at')->with('clearance')
                    ->with('clearance.purpose')
                    ->with('clearance.student')   
                    ->with('clearance.student')  
                    ->with('clearance.student.program') 
                   
                    ->whereHas('clearance.student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    }) 
                    ->orWhereHas('clearance.student', function($q) use ($id){
                        $q->where('student_number', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('clearance.student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('clearance.purpose', function($q) use ($id){
                        $q->where('purpose', 'ILIKE', '%' . $id . '%');
                    })
                    ->whereHas('clearance.student.program', function($q) use($campus){
                        $q->where('campus_id', $campus);
                    })  
                    ->paginate($per_page))  
                ],200);
        }
		}
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
        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return response()->json(['user' => $user],200);
    }
    public function deleteAll(Request $request)
    {
       User::whereIn('id', $request->users)->delete();
        return response()->json(['message' , 'Records Deleted Successfully'],200);
    }
}
