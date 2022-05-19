<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StaffStCouncil;
use App\User;
use App\Designee;
use App\College;
use App\Semester;
use App\UserRole;
use App\Staff;
use App\Http\Resources\StCouncil as StCouncilResource;
use App\Http\Resources\StCouncilCollection;
class StCouncilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $deans =  new StCouncilCollection(StaffStCouncil::orderByDesc('updated_at')->with('user')->with('college')->with('semester')
        ->paginate($per_page));
        return response()->json([
            'stcouncils' => $deans,
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','!=','student');
            }
            )->get(),
            'colleges' => College::orderBY('id')->get(),
            'semesters' => Semester::orderByDesc('created_at')->get(),
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
            'stcouncils' => new StCouncilCollection(
                StaffStCouncil::with('user')->with('semester')->with('college')
                ->whereHas('user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
            ->orWhereHas('college', function($q) use ($id){
                $q->where('short_name', 'ILIKE', '%' . $id . '%')
                ->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('semester', function($q) use ($id){
                $q->where('semester', 'ILIKE', '%' . $id . '%');
            }) 
            ->paginate($per_page)),
            ],200);
        //
    }

    public function store(Request $request)
    { 
           
            $stcouncil = new StaffStCouncil([
            'user_id' => $request->user_id,
            'college_id'=> $request->college_id, 
            'semester_id'=> $request->semester_id,
       
            ]);  
            $stcouncil->save(); 
            $stcouncils =  new StCouncilCollection(StaffStCouncil::orderByDesc('updated_at')->with('user')->with('semester')->with('college')
            ->paginate(5));
            return response()->json(['stcouncils'=>$stcouncils,200]);
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
        $stcouncil = StaffStCouncil::find($id); 
        $stcouncil->user_id = $request->user_id;
        $stcouncil->college_id= $request->college_id; 
        $stcouncil->semester_id= $request->semester_id; 
        $stcouncil->save();  
        
        $designee_id = Designee::orderBy('id')->where('name','Student Council')->first()->id;
        $campus_id = College::find($request->college_id)->id;
        $staff = Staff::firstOrCreate([
            'user_id' => $request->user_id,
            'campus_id'=> $campus_id, 
            'semester_id'=> $request->semester_id,
            'designee_id'=> $designee_id,
            ]);  
        // return response()->json(['stcouncil' => $stcouncil],200);
        return response()->json(['stcouncil'=> new StCouncilResource($stcouncil)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stcouncil = StaffStCouncil::find($id)->delete();
        return response()->json(['stcouncil' => $stcouncil],200);
    } 
    
 
}
