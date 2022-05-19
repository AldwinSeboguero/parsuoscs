<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff_PD;
use App\User;
use App\UserRole;
use App\Designee;
use App\Staff;
use App\Campus;
use App\Semester;
use App\Staff_DEAN; 
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Staff_Adviser;
use App\Http\Resources\Staff as StaffResource;
use App\Http\Resources\StaffCollection;
use App\Http\Resources\OSAS as OSASResource;
use App\Http\Resources\OSASCollection;

class OSASController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
            $per_page =$request->per_page ? $request->per_page : 10; 
            $osas =  new OSASCollection(Staff::orderByDesc('updated_at')->where('designee_id',
            Designee::where('short_name','OSAS')->first()->id)->with('user')->with('semester')->with('designee')->with('campus')
            ->paginate($per_page));
            return response()->json([
                'osass' => $osas,
                'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
                    function ($q){
                        $q->where('name','!=','student');
                    }
                    )->get(),
                    'designations' => Designee::orderBY('id')->get(),
                    'campuses' => Campus::orderBY('id')->get(),
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'osass' => new OSASCollection(
                Staff::orderByDesc('updated_at')->where('designee_id',
                Designee::where('short_name','OSAS')->first()->id)->with('designee')
                ->with('user')->with('semester')->with('campus') 
            ->whereHas('campus', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('semester', function($q) use ($id){
                $q->where('semester', 'ILIKE', '%' . $id . '%');
            }) 
            ->paginate($per_page)),
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','!=','student');
            }
            )->get(),
            'designations' => Designee::orderBY('id')->get(),
            'campuses' => Campus::orderBY('id')->get(),
            'semesters' => Semester::orderByDesc('created_at')->get(),
            ],200);
    }


    public function store(Request $request)
    { 
           
            $staff = Staff::firstOrCreate([
            'user_id' => $request->user_id,
            'campus_id'=> $request->campus_id, 
            'semester_id'=> $request->semester_id,
            'designee_id'=> $request->designee_id,
            ]);  
            $staff->save(); 
            $staffs =  new OSASCollection(Staff::orderByDesc('updated_at')->where('designee_id',
            Designee::where('short_name','OSAS')->first()->id)->with('user')->with('semester')->with('designee')->with('campus')
            ->paginate(5));
            return response()->json(['osass'=>$staffs,200]);
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
        $staff = Staff::find($id); 
        $staff->user_id = $request->user_id;
        $staff->campus_id= $request->campus_id; 
        $staff->semester_id= $request->semester_id;
        $staff->designee_id= $request->designee_id;
        $staff->save();  
        // return response()->json(['staff' => $staff],200);
        return response()->json(['osas'=> new StaffResource($staff)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::find($id)->delete();
        return response()->json(['osas' => $staff],200);
    } 
    

}