<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Student;
use App\Http\Resources\SiasAccount as StudentResource;
use App\Http\Resources\SiasAccountCollection;
use App\Campus;
use App\Program;
use App\Section;
use App\SiasAccount;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Imports\SiasAccountImport; 

use Maatwebsite\Excel\Facades\Excel;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Staff;
use App\Staff_PD;

class SIASAccountController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
 
          
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'siasaccounts'=> new SiasAccountCollection(SiasAccount::orderByDesc('created_at')->with('student')
       
        ->paginate($per_page)),
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
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    { 
            $per_page =$request->per_page ? $request->per_page : 10;
            return response()->json([
                'siasaccounts' => new SiasAccountCollection(SiasAccount::orderByDesc('created_at')->with('student')
                ->where('user_id','ILIKE','%'.$id. '%')
                ->orWhere('password','ILIKE','%'.$id. '%') 
                ->orWhereHas('student', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })
                ->paginate($per_page)), 
                ],200);
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
        $student = Student::find($id);
        $student->name= $request->name;  
        $student->student_number= $request->student_number;  
        $student->year= $request->year;   
        $student->section_id= 1; 
        $program = Program::where('campus_id',$request->campus_id)->where('id',$request->program_id)->first();
        $student->program_id= $program->id;  
        $student->initial_password = $request->code;   
        $student->save();  
        return response()->json(['student'=> new StudentResource($student)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id)->delete();
        return response()->json(['student' => $student],200);
    } 

    public function campusListener(Request $request){
        if(Auth::user()->hasRole("admin")){
           
            $programs = Program::where('campus_id', $request->campus_id)->get(); 
            return response()->json(['programs' => $programs],200);
        }
        else if(Auth::user()->hasRole("pd")){
            $programs = Program::whereIn('id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))->get(); 
            return response()->json(['programs' => $programs],200);
        }
        else{
            $programs = Program::where('campus_id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(); 
            return response()->json(['programs' => $programs],200);
        }
  
    }
    public function programListener(Request $request){
        $sections = Section::where('program_id', $request->program_id)->get(); 
        return response()->json(['sections' => $sections],200);
    }

    public function import(Request $request)
    {
         $request->validate([
            'import_file' => 'required|file|mimes:xls,xlsx'
        ]);

        $path = $request->file('import_file');
        $data = Excel::import(new SiasAccountImport, $path);

        return response()->json(['message' => 'uploaded successfully'], 200);
    }
    public function getAccount(Request $request)
    {
           $student_id = Student::where('user_id',Auth::user()->id)->first();
      $account = new StudentResource(SiasAccount::where('student_id', $student_id->id)->latest('updated_at')->first());

        return response()->json(['account' => $account], 200);
    }

}
