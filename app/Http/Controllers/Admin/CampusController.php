<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Campus;
use App\College;
use App\SignatoryV2;
use Illuminate\Support\Facades\Auth; 

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request)
        {
            return response()->json(['campuses'=> Campus::when(!Auth::user()->hasRole("admin") && !$request->byPass, function($q) use($request){
                $q->whereIn('id',SignatoryV2::where('user_id',Auth::user()->id)->where('semester_id','>=',8)->distinct('campus')->get('campus_id'));
            })
            ->get()],200);
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
            
            $campus = new Campus([
            'name' => $request->name,
            'short_name' => $request->short_name,
            ]);  
            $campus->save(); 
            return response()->json(['campuses'=> Campus::orderByDesc('updated_at')->paginate(10),200]);
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
        $campus = Campus::find($id);
        $campus->name= $request->name;  
        $campus->short_name= $request->short_name;  
        
        $campus->save();  
        return response()->json(['campus' => $campus],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campus = Campus::find($id)->delete();
        return response()->json(['campus' => $campus],200);
    } 
}
