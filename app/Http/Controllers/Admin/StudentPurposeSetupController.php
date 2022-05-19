<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Purpose;
use App\Semester;
use App\Graduation;
use App\StudentPurposeSetup;
use App\Clearance;
use App\ClearancePurpose;
use App\Student;
use Illuminate\Support\Facades\Auth;
class StudentPurposeSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purposes = Purpose::orderBy('id')->get();
        $semesters = Semester::orderByDesc('id')->get();
        $graduations = Graduation::orderBy('id')->get();
        $activeStudentPurposeSetup = StudentPurposeSetup::where('user_id',Auth::user()->id)->first();
        $purpose_id ="";
        $semester_id="";
        $graduation_id ="";
        
        return response()->json([
            'purposes' => $purposes,
            'semesters' => $semesters,
            'graduations' => $graduations,
            'purpose_id' => $activeStudentPurposeSetup ? Purpose::where('purpose',json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->name)->first() : null,
            'semester_id' => $activeStudentPurposeSetup ? Semester::where('semester',json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->description)->first() : null,
            'graduation_id' => $activeStudentPurposeSetup ? Graduation::where('graduation',json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->description)->first() : null,
            'credential' => $activeStudentPurposeSetup ? Purpose::where('purpose',json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->name)->first()->id == 3 ? json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->description : null :null,
        ],200);
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
        
        $student =  Student::where('user_id', Auth::user()->id)->first();
        $activeStudentPurposeSetup = StudentPurposeSetup::where('user_id',Auth::user()->id)->first();
        if($activeStudentPurposeSetup){

            if($request->purpose_id == 1){
                $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                'description' => Semester::find($request->semester_id)->semester];
                }
               else if($request->purpose_id == 2){
                    $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                    'description' => Graduation::find($request->graduation_id)->graduation];
                }
                else{
                    $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                    'description' => $request->credential];
                }
                if(json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->name == $data['name']
                && json_decode(json_decode($activeStudentPurposeSetup->purpose)->purpose)->description == $data['description']
                ){
                    return response()->json(['message'=>"Nothing Changed!"],200);
                }
                else{
                   
                    $purposes = ClearancePurpose::where('student_id' , $student->id)->get();
                    $purpose_exist = 0;
                    $purpose_new = "";
                    foreach ($purposes as $key => $purpose) {
                        if (json_decode($purpose->purpose)->name == $data['name']
                        && json_decode($purpose->purpose)->description == $data['description']
                        ) {
                            $purpose_exist =1;
                            $purpose_new = $purpose;
                        }
                    }
                    if($purpose_exist){
                        $activeStudentPurposeSetup->purpose_id = $purpose_new->id;
                        // $activeStudentPurposeSetup->semester_id = $purpose_new->semester_id;
                        $activeStudentPurposeSetup->save(); 
                    }
                    else{
        
                        $clearancePurpose = new ClearancePurpose([ 
                            'student_id'     => $student->id, 
                            'purpose' => json_encode($data),
                            'semester_id' => Semester::latest('id')->first()->id,
                        ]);  
                        $clearancePurpose->save();
                        $activeStudentPurposeSetup->purpose_id = $clearancePurpose->id;
                        $activeStudentPurposeSetup->save();
                        // $semesterSetup = SemesterSetup::where('user_id',$student->user_id)->where('semester_id', 2)->first();
                        // $semesterSetup->semester_id = 2;
                        // $semesterSetup->save();
                        $exist = Clearance::where('student_id',$student->id)->where('purpose_id',$clearancePurpose->id)->first();
                        if(!$exist){
                        $newClearance = new Clearance([
                            'student_id'=> $student->id, 
                            'purpose_id' =>$clearancePurpose->id, 
                        ]);
                        $newClearance->save();
                        }
                    }
                    
                    
                    return response()->json(['data'=>$data],200);
                }
        }
        else{
            if($request->purpose_id == 1){
                $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                'description' => Semester::find($request->semester_id)->semester];
                }
               else if($request->purpose_id == 2){
                    $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                    'description' => Graduation::find($request->graduation_id)->graduation];
                }
                else{
                    $data = ['name' => Purpose::find($request->purpose_id)->purpose,
                    'description' => $request->credential];
                }
                $clearancePurpose = new ClearancePurpose([ 
                    'student_id'     => $student->id, 
                    'purpose' => json_encode($data),
                    'semester_id' => Semester::latest('id')->first()->id,
                ]);  
                $clearancePurpose->save();
                $newClearance = new Clearance([
                    'student_id'=> Student::where('user_id',Auth::user()->id)->first()->id, 
                    'purpose_id' =>$clearancePurpose->id,  
                ]);
                $newClearance->save();
                $studentPurposeSetup = StudentPurposeSetup::whereUser_id(Auth::user()->id)->first();
                $studentPurposeSetup['status'] = 1;
                $studentPurposeSetup['purpose_id'] = $clearancePurpose->id;   
                $studentPurposeSetup['user_id'] =Auth::user()->id;       
            StudentPurposeSetup::insert($studentPurposeSetup); 
                return response()->json(['data'=>$data],200);
        }
        

        
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
