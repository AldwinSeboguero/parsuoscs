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
use App\ClearanceRequestV2;
use App\Exports\StudentActivationCode;
use App\Http\Resources\ProgramCollection;

use App\Role;
use App\UserRole;

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
        'duplicateRequest' => DB::table('clearance_request_v2')
        ->select(
            'student_id',
            'designee_id',
            'purpose_id', DB::raw('COUNT(*) as count'))
        ->groupBy(
            'student_id',
            'designee_id',
            'purpose_id')
        ->havingRaw('COUNT(*) > 1')
        ->get(),
        ],200);
    
        }
        // else if(Auth::user()->hasRole("pd")){
           
        // $per_page =$request->per_page ? $request->per_page : 10; 
        // return response()->json([
        // 'students'=> new StudentCollection(Student::orderByDesc('created_at')->with('section')->with('deficiencies')->with('program')->with('program.campus')
        
        // ->whereIn('program_id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))
        // // ->whereHas('program.campus', function($q){
        // //     $q->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id);
        // // }) 
        // ->paginate($per_page)),
        // 'campuses' => Campus::orderBy('name')->where('id', Staff::where('user_id',Auth::user()->id)->first()->campus_id)->get(),
        // 'programs' => Program::orderBy('name')->whereIn('id',Staff_PD::where('user_id',Auth::user()->id)->get('program_id'))->get(),
        // 'sections' => Section::orderBy('name')->get(),
        // ],200);

        // }
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
        $student_id = $request->student_id;
        $name = $request->name;
        $program_id = $request->program_id;
        $campus_id = $request->campus_id;
        $college_id = $request->college_id;
        $email = $request->email;

        $isAccountExits = User::where('email',$email)->get()->count();
        $isStudentExits = Student::when($student_id, function ($q) use ($student_id){
            $q->where('student_number','ilike','%'.$student_id.'%');
        })
        ->when($name, function ($q) use ($name){
            $q->where('name','ilike','%'.$name.'%');
        })
        ->when($campus_id, function ($q) use ($campus_id){
            $q->whereHas('program',function($q) use($campus_id){
                $q->where('campus_id',$campus_id);
            });
        })
        ->when($college_id, function ($q) use ($college_id){
            $q->whereHas('program',function($q) use($college_id){
                $q->where('college_id',$college_id);
            });
        })
        ->when($program_id, function ($q) use ($program_id){
            $q->where('program_id',$program_id);
        })
        ->when($email, function ($q) use ($email){
                $q->has('user')->wherehas('user', function($q) use($email){
                    $q->where('email','ilike','%'.$email.'%');
                });
        })->get()->count();

        if($isAccountExits == 0 && $isStudentExits == 0){
            $role = Role::where('description','Student')->first();
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($student_id), 
                'username' => $email,
            ]);
            $user->save();
            $user->roles()->attach($role);
            $userRole = UserRole::where('user_id',$user->id)->where('role_id',$role->id)->first();

            $student = Student::updateOrCreate([
                'student_number'    => $student_id,
                'slug'    => str_replace(".","",str_replace(",","",str_replace(" ","", $campus_id))).'-'.$student_id.'-'.str_replace(".","",str_replace(",","",str_replace(" ","", $name))),
                'name'    => $name,
                'year'    => 1,
                'section_id'     => 1, 
                'program_id'     => $program_id, 
                'initial_password' => Str::uuid()->toString(),
                'semester_id' => Semester::latest('id')->first()->id,
                'user_id' => $user->id,
    
            ]);
           
            return $student;
        }
        elseif($isAccountExits != 0 ){
            return response()->json([
                'alert' => true,
                'message' => 'Email Already Exist!'
            ],500);
        }
        elseif($isStudentExits != 0 ){
            return response()->json([
                'alert' => true,
                'count' => $isStudentExits,
                'message' => 'Student Already Exist! Please check student data.'
            ],500);
        }
        else{
            return response()->json([
                'alert' => true,
                'message' => 'Student Record Added Successfully.'
            ],200);
        }
            // $student = new Student([
            // 'student_number' => $request->student_number,
            // 'name' => $request->name,
            // 'slug' => $request->student_number.'-'.str_replace(".","",str_replace(",","",str_replace(" ","",$request->name))),
            // 'name' => $request->name,
            // 'year'    => 1,
            // 'section_id'     => 1, 
            // 'program_id'     => $request->program_id, 
            // 'initial_password' => Str::uuid()->toString(),
            // ]);  
            // $student->save(); 
            // return $this->index($request);
            
    }
    public function getStudents(Request $request){
        
        $student_id = $request->student_id;
        $name = $request->name;
        $program_id = $request->program_id;
        $campus_id = $request->campus_id;
        $college_id = $request->college_id;
        $email = $request->email;



        $students =  new StudentCollection(Student::orderByDesc('updated_at')
        ->when($student_id, function ($q) use ($student_id){
            $q->where('student_number','ilike','%'.$student_id.'%');
        })
        ->when($name, function ($q) use ($name){
            $q->where('name','ilike','%'.$name.'%');
        })
        ->when($campus_id, function ($q) use ($campus_id){
            $q->whereHas('program',function($q) use($campus_id){
                $q->where('campus_id',$campus_id);
            });
        })
        ->when($college_id, function ($q) use ($college_id){
            $q->whereHas('program',function($q) use($college_id){
                $q->where('college_id',$college_id);
            });
        })
        ->when($program_id, function ($q) use ($program_id){
            $q->where('program_id',$program_id);
        })
        ->when($email, function ($q) use ($email){
                $q->has('user')->wherehas('user', function($q) use($email){
                    $q->where('email','ilike','%'.$email.'%');
                });
        })
        ->paginate($request->per_page));
        return response()->json([
            'table_data' => $students,
            'headers' => [
                [ 'text'=> 'Actions', 
                  'sortable'=> false,
                  'value'=> 'actions',
                //   'class'=> 'blue darken-4 white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [
                  'text'=> '#',
                  'align'=> 'start',
                  'sortable'=> false,
                  'value'=> 'id',
                //   'class'=> 'blue darken-4 white--text',
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Student ID', 
                  'sortable'=> false,
                  'value'=> 'student_id',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Name', 
                  'sortable'=> false,
                  'value'=> 'name',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Email', 
                  'sortable'=> false,
                  'value'=> 'email',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Program', 
                  'sortable'=> false,
                  'value'=> 'program',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                
                [ 'text'=> 'Campus', 
                  'sortable'=> false,
                  'value'=> 'campus',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                  
              ],
        ]);
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
        $student_id = $request->student_id;
        $name = $request->name;
        $program_id = $request->program_id;
        $campus_id = $request->campus_id;
        $college_id = $request->college_id;
        $email = $request->email;

        $student = Student::find($id);
        $student->name= $name;  
        $student->student_number= $student_id;  
        $student->year= 1;   
        $student->section_id= 1; 
        // $program = Program::where('campus_id',$request->campus_id)->where('id',$request->program_id)->first();
        $student->program_id= $program_id;       
        $student->save();  

        if($student->user_id && $student->user->email != $email){
            User::where('email',$student->user->email)->update([
                'email' => $email,
            ]);
        }
        // $isAccountExits = User::where('email',$email)->get()->count();
        // // return response()->json(['student'=> new StudentResource($student)],200);
        // if($isAccountExits != 0 ){
        //     return response()->json([
        //         'alert' => true,
        //         'message' => 'Email Already Exist!'
        //     ],500);
        // }
        // else{
            return response()->json([
                'alert' => true,
                'message' => 'Student Record Update Successfully.'
            ],200);
        // }
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
