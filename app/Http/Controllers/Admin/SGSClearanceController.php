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
use App\College;
class SGSClearanceController extends Controller
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
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderByDesc('updated_at')->
            with('student')
            ->with('student.program')
            
            ->whereHas('student.program', function($q){
                $q->where('college_id', College::where('short_name','SGSS')->first()->id);
            })  
            ->with('staff')
            ->with('staff.user')->orderBy('request_at')
            ->where('status', 0)->with('purpose') 
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            
            
            ->paginate($per_page)),
            
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
   
            return response()->json([
                'clearancerequests' => new ClearanceRequestCollection(
                    ClearanceRequest::where('status', false)
                    ->where('staff_id',Staff::where('user_id',Auth::user()->id)->first()->id)
                    ->with('purpose')->with('student')->with('student.deficiencies')
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
                        $q->where( 'purpose->name',$id );
                    })
                    ->paginate(10))  
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
    public function update($id,Request $request)
    {
        $clearanceRequest = ClearanceRequest::find($id);
        $student = Student::find($clearanceRequest->student_id);
        $d = Deficiency::updateOrCreate([
        'title' => $request->title,   
        'note' => $request->note,   
        'staff_id' => $clearanceRequest->staff_id,   
        'designee_id' => $clearanceRequest->staff->designee_id,   
        'student_id' => $clearanceRequest->student_id, 
        'semester_id' => $clearanceRequest->purpose->semester_id, 
        'purpose_id' => $clearanceRequest->purpose_id,    
        ]); 
        $student->deficiencies()->attach($d);
       //update request status
        $clearanceRequest->status = 2;
       $clearanceRequest->save();
       
       
       return response()->json([
        'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderByDesc('updated_at')->
        with('student')
        ->with('student.program')
        
        ->whereHas('student.program', function($q){
            $q->where('college_id', College::where('short_name','SGSS')->first()->id);
        })  
        ->with('staff')
        ->with('staff.user')->orderBy('request_at')
        ->where('status', 0)->with('purpose') 
        ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
        
        
        ->paginate($per_page)),
        'user' => Auth::user(), 
        
        ],200);

    }
    public function approveRequest(Request $request)
    {
       
        $clearanceRequest = ClearanceRequest::find($request->id);
        $clearanceRequest->status = true;
        $clearanceRequest->approved_at = now();
        $clearanceRequest->save();

        $clearance = Clearance::where('student_id',$clearanceRequest->student_id)->where('purpose_id',$clearanceRequest->purpose_id)->first();
        if(Auth::user()->hasRole("pd")){
        $clearance->program_director = true;
        $clearance->save();
        }
        else if(Auth::user()->hasRole("dean")){
            $clearance->college_deaan = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("stcouncil")){
            $clearance->student_council = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("cashier")){
            $clearance->cashier = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("osas")){
            $clearance->osas = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("library")){
            $clearance->library = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("registrar")){
            $clearance->registrar = true;
            $clearance->save();
        }
        else if(Auth::user()->hasRole("registrarstaff")){
            $clearance->registrar = true;
            $clearance->save();
        }

        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10; 
            return response()->json([
                'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
                ->where('status', 0)->with('purpose')  
                ->with('staff')
                ->with('staff.user') 
                ->paginate($per_page)),
                'user' => Auth::user(), 
                
                ],200);
        }
        else{
        $per_page =$request->per_page ? $request->per_page : 10; 
       
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderByDesc('updated_at')->
            with('student')
            ->with('student.program')
            
            ->whereHas('student.program', function($q){
                $q->where('college_id', College::where('short_name','SGSS')->first()->id);
            })  
            ->with('staff')
            ->with('staff.user')->orderBy('request_at')
            ->where('status', 0)->with('purpose') 
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            
            
            ->paginate($per_page)),
            'user' => Auth::user(), 
            
            ],200);
        }
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
