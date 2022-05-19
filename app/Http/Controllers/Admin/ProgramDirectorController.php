<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff_PD;
use App\User;
use App\Designee;
use App\College;
use App\Semester;
use App\UserRole;
use App\Staff;
use App\Campus;
use App\Program;
use App\Http\Resources\programdirector as ProgramDirectorResource;
use App\Http\Resources\ProgramDirectorCollection;
class ProgramDirectorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $programdirectors =  new ProgramDirectorCollection(Staff_PD::with('user')->with('semester')->with('program')->with('program.campus')
        ->paginate($per_page));
        return response()->json([
            'programdirectors' => $programdirectors,
            'user_pd' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','pd');
            }
            )->get(),
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','!=','student');
            }
            )->get(),
            'campuses' => Campus::orderBY('id')->get(),
            'programs' => Program::orderBY('id')->get(),
            'semesters' => Semester::orderByDesc('created_at')->get(),
            ],200);
    }
    public function changePD(Request $request){
        $pd = Staff_PD::find($request->pd);
        $staff = $pd;
        $new_pd = $request->new_pd;
        // $changeStaff =Staff::where('user_id',$staff->user_id)->where('semester_id',$staff->semester_id)
        // ->first();
        // // dd($changeStaff->user_id);
        // $changeStaff->user_id = $new_pd; 
        // $changeStaff->save(); 
        $pd->user_id=$new_pd; 
        $pd->save();
      
        $per_page =$request->per_page ? $request->per_page : 10; 
        $pds =  new ProgramDirectorCollection(Staff_PD::with('user')->with('program')->with('semester')
        ->paginate($per_page));
        return response()->json([
            'programdirectors' => $pds,
            'user_pd' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','pd');
            }
            )->get(),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'programdirectors' => new ProgramDirectorCollection(
                Staff_PD::with('user')->with('semester')->with('program')->with('program.campus')
                ->whereHas('user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
            ->orwhereHas('program.campus', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('program', function($q) use ($id){
                $q->where('short_name', 'ILIKE', '%' . $id . '%')
                ->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('semester', function($q) use ($id){
                $q->where('semester', 'ILIKE', '%' . $id . '%');
            }) 
            ->paginate($per_page)),
            'user_pd' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','pd');
            }
            )->get(),
            ],200);
        //
    }

    public function store(Request $request)
    { 
           
            $programdirector = Staff_PD::firstOrCreate([
            'user_id' => $request->user_id,
            'program_id'=> $request->program_id, 
            'semester_id'=> $request->semester_id,
       
            ]);  
            $programDirectors =  new ProgramDirectorCollection(Staff_PD::orderByDesc('updated_at')->with('user')->with('semester')->with('college')
            ->paginate(5));
            return response()->json(['programDirectors'=>$programDirectors,200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

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
        $programdirector = Staff_PD::find($id); 
        $programdirector->user_id = $request->user_id;
        $programdirector->program_id= $request->program_id; 
        $programdirector->semester_id= $request->semester_id; 
        $programdirector->save();  
        
        $designee_id = Designee::orderBy('id')->where('name','Program Director')->first()->id;
      
        $staff = Staff::firstOrCreate([
            'user_id' => $request->user_id,
            'campus_id'=> $request->campus_id, 
            'semester_id'=> $request->semester_id,
            'designee_id'=> $designee_id,
            ]);  
        // return response()->json(['programdirector' => $programdirector],200);
        return response()->json(['programdirector'=> new ProgramDirectorResource($programdirector)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $programdirector = Staff_PD::find($id)->delete();
        return response()->json(['programdirector' => $programdirector],200);
    } 
    
    public function campusListener(Request $request){

            $programs = Program::where('campus_id', $request->campus_id)->get(); 
            return response()->json(['programs' => $programs],200);
      
  
    }
}
