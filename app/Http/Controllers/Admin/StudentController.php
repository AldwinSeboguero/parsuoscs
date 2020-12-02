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
class StudentController extends Controller
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
        'students'=> new StudentCollection(Student::orderByDesc('updated_at')->with('section')->with('program')->with('program.campus')->paginate($per_page)),
        'campuses' => Campus::orderBy('name')->get(),
        'programs' => Program::orderBy('name')->get(),
        'sections' => Section::orderBy('name')->get(),
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
            
            $student = new Student([
            'student_number' => $request->student_number,
            'name' => $request->name,
            'slug' => $request->student_number.'-'.str_replace(".","",str_replace(",","",str_replace(" ","",$request->name))),
            'name' => $request->name,
            'year'    => $request->year,
            'section_id'     => $request->section, 
            'program_id'     => $request->program, 
            'initial_password' => Str::uuid()->toString(),
            ]);  
            $student->save(); 
            return response()->json(['students'=> new StudentCollection(Student::orderByDesc('updated_at')->paginate()),
            'student'=> new StudentResource($student)],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'students' => new StudentCollection(Student::with('section')
            ->with('program')
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
            ->orWhereHas('program.campus', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('program.campus', function($q) use ($id){
                $q->where('short_name', 'ILIKE', '%' . $id . '%');
            })
            ->orWhereHas('section', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            })  
            ->paginate(10)),
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
        $student->section_id= $request->section_id; 
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
        $programs = Program::where('campus_id', $request->campus_id)->get(); 
        return response()->json(['programs' => $programs],200);
    }
    public function programListener(Request $request){
        $sections = Section::where('program_id', $request->program_id)->get(); 
        return response()->json(['sections' => $sections],200);
    }
}
