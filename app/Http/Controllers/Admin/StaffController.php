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

class StaffController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $staffs =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')->with('designee')
        ->paginate($per_page));
        return response()->json([
            'staffs' => $staffs,
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
    public function changeStaff(Request $request){
        // $staff = Staff::find($request->staff); 
        // $new_staff = $request->new_staff;
        // // $changeStaff =Staff::where('user_id',$staff->user_id)->where('semester_id',$staff->semester_id)
        // // ->first();
        // // // dd($changeStaff->user_id);
        // // $changeStaff->user_id = $new_pd; 
        // // $changeStaff->save(); 
        // $staff->user_id=$new_staff; 
        // $staff->save();
      
        // $per_page =$request->per_page ? $request->per_page : 10; 
        // $staff =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')
        // ->paginate($per_page));
        // return response()->json([
        //     'staffs' => $staff,
        //     ],200);

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
            'staffs' => new StaffCollection(
                Staff::with('user')->with('semester')->with('campus')
                ->whereHas('user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
            ->orwhereHas('campus', function($q) use ($id){
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
            )
            ->whereHas('user', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->get(),
            'designations' => Designee::orderBY('id')->get(),
            'campuses' => Campus::orderBY('id')->get(),
            'semesters' => Semester::orderBY('id')->get(),
            ],200);
        //
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
            $staffs =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')->with('designee')
            ->paginate(5));
            return response()->json(['staffs'=>$staffs,200]);
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
        return response()->json(['staff'=> new StaffResource($staff)],200);
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
        return response()->json(['staff' => $staff],200);
    } 
    

    public function copyPreviousStaff(Request $request){
        $prevsem_id = Semester::orderByDesc('id')->skip(1)->take(1)->get()->first()->id;
        $staffs = Staff::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_deans = Staff_DEAN::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_pds = Staff_PD::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_registrars = StaffRegistrar::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_stcouncils = StaffStCouncil::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_advisers = Staff_Adviser::orderBy('id')->where('semester_id',$prevsem_id)->get();
        foreach ($staffs as $key => $staff) {
            $st = Staff::firstOrCreate([
                'user_id' => $staff->user_id,
                'designee_id' => $staff->designee_id,
                'campus_id' => $staff->campus_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        // dd($staff_deans);
        foreach ($staff_deans as $key => $staff) {
            $st = Staff_DEAN::firstOrCreate([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_pds as $key => $staff) {
            $st = Staff_PD::firstOrCreate([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_registrars as $key => $staff) {
            $st = StaffRegistrar::firstOrCreate([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_stcouncils as $key => $staff) {
            $st = StaffStCouncil::firstOrCreate([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_advisers as $key => $staff) {
            $st = Staff_Adviser::firstOrCreate([
                'user_id' => $staff->user_id, 
                'section_id' => $staff->section_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        $staffs =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')->with('designee')
        ->paginate(5));
        return response()->json(['staffs'=>$staffs,200]);
        // return response()->json(['Prev Sem'=>Semester::orderByDesc('id')->skip(1)->take(1)->get(),
        // 'New Sem' => $request->new_semester_id,
        // 200]);
    }
}
