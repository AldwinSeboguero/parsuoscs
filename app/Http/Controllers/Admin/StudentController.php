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
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use App\Imports\StudentsImport; 
use Maatwebsite\Excel\Facades\Excel;
use DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Staff;
use App\Staff_PD;
use App\Semester;
use App\User;
use App\Exports\StudentActivationCode;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->hasRole("admin")){
          
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'students'=> new StudentCollection(Student::orderByDesc('created_at')->with('section')->with('deficiencies')->with('program')->with('program.campus')
       
        ->paginate($per_page)),
        'campuses' => Campus::orderBy('name')->get(),
        'programs' => Program::orderBy('name')->get(),
        'sections' => Section::orderBy('name')->get(),
        ],200);
    
        }
        else if(Auth::user()->hasRole("pd")){
           
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'students'=> new StudentCollection(Student::orderByDesc('created_at')->with('section')->with('deficiencies')->with('program')->with('program.campus')
        
        ->whereIn('program_id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))
        // ->whereHas('program.campus', function($q){
        //     $q->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id);
        // }) 
        ->paginate($per_page)),
        'campuses' => Campus::orderBy('name')->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(),
        'programs' => Program::orderBy('name')->whereIn('id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))->get(),
        'sections' => Section::orderBy('name')->get(),
        ],200);

        }
        else{
          
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'students'=> new StudentCollection(Student::orderByDesc('created_at')->with('section')->with('deficiencies')->with('program')->with('program.campus')
        ->whereHas('program.campus', function($q){
            $q->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id);
        }) 
        ->paginate($per_page)),
        'campuses' => Campus::orderBy('name')->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(),
        'programs' => Program::orderBy('name')->get(),
        'sections' => Section::orderBy('name')->get(),
        ],200);
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
            
            $student = new Student([
            'student_number' => $request->student_number,
            'name' => $request->name,
            'slug' => $request->student_number.'-'.str_replace(".","",str_replace(",","",str_replace(" ","",$request->name))),
            'name' => $request->name,
            'year'    => $request->year,
            'section_id'     => 1, 
            'program_id'     => $request->program_id, 
            'initial_password' => Str::uuid()->toString(),
            ]);  
            $student->save(); 
            return response()->json(['students'=> new StudentCollection(Student::orderByDesc('created_at')->paginate()),
            'student'=> new StudentResource($student)],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10;
            return response()->json([
                'students' => new StudentCollection(Student::orderBy('created_at')->with('section')
                ->with('program')
                ->with('deficiencies')
                ->with('program.campus') 
                ->where('student_number','ILIKE','%'.$id. '%')
                ->orWhere('name','ILIKE','%'.$id. '%')
                ->orWhere('year','ILIKE','%'.$id. '%')
                ->orWhere('initial_password','ILIKE','%'.$id. '%')
                ->orWhereHas('program', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })
                ->orWhereHas('program', function($q) use ($id){
                    $q->where('short_name', 'ILIKE', '%' . $id . '%');
                })
                ->whereHas('program.campus', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
                ->orWhereHas('section', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
                ->paginate($per_page)),
                'campuses' => Campus::orderBy('name')->get(),
                'programs' => Program::orderBy('name')->get(),
                'sections' => Section::orderBy('name')->get(),
                ],200);
    
        }
        else if(Auth::user()->hasRole("pd")){
            $per_page =$request->per_page ? $request->per_page : 10;
            return response()->json([
                'students' => new StudentCollection(Student::orderBy('created_at')->with('section')
                ->with('program')
                ->with('deficiencies')
                ->with('program.campus') 
                ->where('student_number','ILIKE','%'.$id. '%')
                ->whereIn('program_id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))
                ->orWhere('name','ILIKE','%'.$id. '%')
                ->whereHas('program.campus', function($q){
                    $q->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id);
                }) 
                ->orWhere('year','ILIKE','%'.$id. '%')
                ->orWhere('initial_password','ILIKE','%'.$id. '%')
                // ->orWhereHas('program', function($q) use ($id){
                //     $q->where('name', 'ILIKE', '%' . $id . '%');
                // })
                // ->orWhereHas('program', function($q) use ($id){
                //     $q->where('short_name', 'ILIKE', '%' . $id . '%');
                // })
                
                ->orWhereHas('section', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
                ->paginate($per_page)),
                'campuses' => Campus::orderBy('name')->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(),
                'programs' => Program::orderBy('name')->whereIn('id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))->get(),
                'sections' => Section::orderBy('name')->get(),
                ],200);

        }
        else{
          
            $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'students' => new StudentCollection(Student::orderBy('created_at')->with('section')
            ->with('program')
            ->with('deficiencies')
            ->with('program.campus') 
            ->where('student_number','ILIKE','%'.$id. '%')
         
            ->orWhere('name','ILIKE','%'.$id. '%')
            ->whereHas('program.campus', function($q){
                $q->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id);
            }) 
            ->orWhere('year','ILIKE','%'.$id. '%')
            ->orWhere('initial_password','ILIKE','%'.$id. '%')
            ->orWhereHas('program', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            })
            // ->orWhereHas('program', function($q) use ($id){
            //     $q->where('short_name', 'ILIKE', '%' . $id . '%');
            // })
            
            ->orWhereHas('section', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            })  
            ->paginate($per_page)),
            'campuses' => Campus::orderBy('name')->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(),
            'programs' => Program::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            ],200);
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
        $data = Excel::import(new StudentsImport, $path);
        $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'students'=> new StudentCollection(Student::orderByDesc('created_at')->with('section')->with('deficiencies')->with('program')->with('program.campus')
           
            ->paginate($per_page)),
            'campuses' => Campus::orderBy('name')->get(),
            'programs' => Program::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            ],200);
    }

    public function export(Request $request)
    {
        return Excel::download(new StudentActivationCode((int)$request->export_campus_id,(int)$request->export_program_id!=0 ? (int)$request->export_program_id: null),'activation_code.xlsx');
    }
    public function filename(Request $request)
    {
        // dd(Campus::where('id',$request->export_campus_id)->get());
        $filename = ($request->export_campus_id || $request->export_program_id ?Campus::where('id',(int)$request->export_campus_id)->first()->name.'_'.($request->export_program_id ? Program::where('id',(int)$request->export_program_id)->first()->short_name: '' ): '').''.'Activation_Code_'.Semester::latest('id')->first()->semester;
        return response()->json(['filename' => $filename],200);
    }
    public function resetP(Request $request)
    {
        $ranPass = Str::random(8);
        $user = User::find($request->user_id);
        $user->password = Hash::make($ranPass);
        $user->save();
        return response()->json(['email' => $user->email,'password' => $ranPass],200);
    }
}
