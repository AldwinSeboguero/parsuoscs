<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff_DEAN;
use App\User;
use App\Designee;
use App\Staff;
use App\College;
use App\Semester;
use App\Http\Resources\Dean as DeanResource;
use App\Http\Resources\DeanCollection;
use App\UserRole;
class DeanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $deans =  new DeanCollection(Staff_DEAN::orderByDesc('updated_at')->with('user')->with('college')->with('semester')
        ->paginate($per_page));
        return response()->json([
            'deans' => $deans,
            'user_dean' => UserRole::orderByDesc('updated_at')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','dean');
            }
            
            )->get(),
            'colleges' => College::orderBY('id')->get(),
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','!=','student');
            }
            )->get(),
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
            'deans' => new DeanCollection(
                Staff_DEAN::orderByDesc('updated_at')->with('user')->with('semester')->with('college')
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
            'user_dean' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','dean');
            }  )->get(),
            ],200);
        //
    }
    public function changeDean(Request $request){
        $dean = Staff_DEAN::find($request->dean);
        $staff = $dean;
        
        $changeStaff =Staff::where('user_id',$staff->user_id)->where('semester_id',$staff->semester_id)
        ->where('designee_id',Designee::where('name','College Dean')->first()->id)->first();
        // dd($staff);
        $changeStaff->user_id =$request->new_dean; 
        $changeStaff->save(); 
        $dean->user_id=$request->new_dean; 
        $dean->save();
      
        $per_page =$request->per_page ? $request->per_page : 10; 
        $deans =  new DeanCollection(Staff_DEAN::with('user')->with('college')->with('semester')
        ->paginate($per_page));
        return response()->json([
            'deans' => $deans,
            'user_dean' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','dean');
            }
            )->get(),
            ],200);

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    { 
           
            $dean = Staff_DEAN::firstOrCreate([
            'user_id' => $request->user_id,
            'college_id'=> $request->college_id, 
            'semester_id'=> $request->semester_id,
       
            ]);  
            $deans =  new DeanCollection(Staff_DEAN::orderByDesc('updated_at')->with('user')->with('college')->with('semester')
            ->paginate(5));
            
            return response()->json(['deans'=>$deans,200]);
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
        $dean = Staff_DEAN::find($id); 
        $dean->user_id = $request->user_id;
        $dean->college_id= $request->college_id; 
        $dean->semester_id= $request->semester_id; 
        $dean->save();  
        
        $designee_id = Designee::orderBy('id')->where('name','College Dean')->first()->id;
      
        $staff = Staff::firstOrCreate([
            'user_id' => $request->user_id,
            'campus_id'=> College::find($request->college_id)->campus_id, 
            'semester_id'=> $request->semester_id,
            'designee_id'=> $designee_id,
            ]);  
        // return response()->json(['dean' => $dean],200);
        return response()->json(['dean'=> new DeanResource($dean)],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dean = Staff_DEAN::find($id)->delete();
        return response()->json(['dean' => $dean],200);
    } 
    
    public function campusListener(Request $request){

            $programs = Program::where('campus_id', $request->campus_id)->get(); 
            return response()->json(['programs' => $programs],200);
      
  
    }
}
