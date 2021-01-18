<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\ClearanceRequest; 
use App\Role; 
use App\User; 
use App\Student; 
use App\ClearancePurpose;
use App\Deficiency; 
use App\Staff;
use App\Clearance;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Pagination\Paginator;
use App\Http\Resources\ClearanceRequest as ClearanceRequestResource;
use App\Http\Resources\ClearanceRequestCollection; 
use Illuminate\Pagination\LengthAwarePaginator;
use App\Support\Collection;
use App\StudentPurposeSetup;
use DB;
use Illuminate\Support\Facades\Auth;
class CompletedClearanceController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10; 
            
            return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
            ->where('status', true)->with('purpose')  
            ->with('staff')
            ->with('staff.user') 
            ->paginate($per_page)),
            'user' => Auth::user(), 
            ],200);
        }
        else{
        $per_page =$request->per_page ? $request->per_page : 10; 
         
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
            ->where('status', true)->with('purpose')   
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            ->with('staff')
            ->with('staff.user') 
            ->paginate($per_page)),
            'user' => Auth::user(), 
            
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'username' => $request->username,
            ]);
            $role =Role::select('id')->where('name','user')->first();
            $user->roles()->attach($role);
            return response()->json(['user'=>$user],200);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $per_page =$request->per_page ? $request->per_page : 10; 
        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10; 
           
            return response()->json([
                'clearancerequests' => new ClearanceRequestCollection(
                    ClearanceRequest::where('status', true)
                    ->with('purpose')->with('student')
                    ->with('student.program')
                    ->with('staff')
                    ->with('staff.user') 
                    ->whereHas('student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('student', function($q) use ($id){
                        $q->where('student_number', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('staff.user', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    })  
                    ->orWhereHas('purpose', function($q) use ($id){
                        $q->where('purpose', 'ILIKE', '%' . $id . '%');
                    })
                    ->paginate(10))  
                ],200);
        }
        else{
        $per_page =$request->per_page ? $request->per_page : 10; 
         
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
            ->where('status', true)->with('purpose')   
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            ->with('staff')
            ->with('staff.user') 
            ->paginate($per_page)),
            'user' => Auth::user(), 
            
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->username = $request->username;
        $user->save();
        
        return response()->json(['user' => $user],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return response()->json(['user' => $user],200);
    }
    public function deleteAll(Request $request)
    {
       User::whereIn('id', $request->users)->delete();
        return response()->json(['message' , 'Records Deleted Successfully'],200);
    }
}
