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
use App\Staff_PD;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Pagination\Paginator;
use App\Http\Resources\ClearanceRequest as ClearanceRequestResource;
use App\Http\Resources\ClearanceRequestCollection; 
use Illuminate\Pagination\LengthAwarePaginator;
use App\Support\Collection;
use App\StudentPurposeSetup;
use DB;
use Illuminate\Support\Facades\Auth;
use App\ClearanceRequestV2;
use App\SignatoryV2;
use App\Semester;
use App\Program;
use App\College;
class ClearanceRequestController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 
    public function index(Request $request){
        $per_page =$request->per_page ? $request->per_page : 10; 
        // $signatory_ids = SignatoryV2::where('user_id',Auth::user()->id)->get('id');

        $clearance_requests =new ClearanceRequestCollection( ClearanceRequestV2::orderBy('requested_at')
                                                ->where('status', false)
                                                ->when($request->search, function($inner) use($request){
                                                    $inner->whereHas('student', function($q) use ($request){
                                                        $q->where('name', 'ILIKE', '%' . $request->search . '%')
                                                        ->orWhere('student_number', 'ILIKE', '%' . $request->search . '%');
                                                    });
                                                })
                                                ->when($request->semester, function($inner) use($request){
                                                    $inner->whereHas('purpose',function($q) use($request){
                                                        $q->where('semester_id',$request->semester);
                                                    });
                                                }) 
                                                ->when($request->college, function($inner) use($request){
                                                    $inner->whereHas('student.program',function($q) use($request){
                                                        $q->where('college_id',$request->college);
                                                    });
                                                }) 
                                                ->when($request->program, function($inner) use($request){
                                                    $inner->whereHas('student',function($q) use($request){
                                                        $q->where('program_id',$request->program);
                                                    });
                                                })

                                                ->where('status', false)
                                                ->paginate($per_page));

        return response()->json([
            // 'signatory' => $signatory_ids->count(),
            'clearance_requests' => $clearance_requests,
            'semester' => $request->semester,
            'college' =>$request->college,
            'program' =>$request->program,

            'semesters' => Semester::orderByDesc('id')->get(),
            'colleges' => College::orderBy('id')
                                                        ->get(),
            'programs' => $request->college ? Program::orderBy('id')
                                                        ->when($request->college, function($inner) use($request){
                                                            $inner->where('college_id',$request->college);
                                                        }) 
                                                        ->get() : [],
        ]);
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
        // if(Auth::user()->hasRole("admin")){
        // return response()->json([
        //       'clearancerequests' => new ClearanceRequestCollection(
        //         ClearanceRequest::where('status', false) 
        //         ->where('status', false)->with('purpose')    
        //         ->whereHas('student', function($q) use ($id){
        //             $q->where('name', 'ILIKE', '%' . $id . '%');
        //         }) 
                 
        //         ->orWhereHas('student', function($q) use ($id){
        //             $q->where('student_number', 'ILIKE', '%' . $id . '%');
        //         })   
        //         ->where('status', false)->with('purpose')  
        //         ->paginate(10)), 
        //     ],200);
        // }
        // else{
        //     return response()->json([
        //         'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
        //         ->where('status', false)->with('purpose')   
        //         ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id'))
        //         ->whereHas('student', function($q) use ($id){
        //             $q->where('name', 'ILIKE', '%' . $id . '%');
        //         }) 
                 
        //         ->orWhereHas('student', function($q) use ($id){
        //             $q->where('student_number', 'ILIKE', '%' . $id . '%');
        //         })  
        //         ->where('status', false)->with('purpose')  
        //         ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
        //         ->paginate(10)),
        //         'user' => Auth::user(), 
        //         ],200); 
        // }
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
        'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
        ->where('status', 0)->with('purpose')   
        ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
        ->with('staff')
        ->with('staff.user') 
        
        ->paginate(10)),
        'user' => Auth::user(), 
        
        ],200);

    }
    public function approveRequest(Request $request)
    {
       
        $clearanceRequest = ClearanceRequest::with('staff')->find($request->id);
        // dd($clearanceRequest->staff->designee->short_name);
        $clearanceRequest->status = true;
        $clearanceRequest->approved_at = now();
        $clearanceRequest->save();
        
        $clearance = Clearance::where('student_id',$clearanceRequest->student_id)->where('purpose_id',$clearanceRequest->purpose_id)->first();
        if($clearanceRequest->staff->designee->short_name == "pd"){
        $clearance->program_director = true;
        $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "dean"){
            $clearance->college_deaan = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "stcouncil"){
            $clearance->student_council = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "cashier"){
            $clearance->cashier = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "OSAS"){
            $clearance->osas = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "library"){
            $clearance->library = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "registrar"){
            $clearance->registrar = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "registrarstaff"){
            $clearance->registrar = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "adviser"){
            $clearance->class_adviser = true;
            $clearance->save();
        }
        else if($clearanceRequest->staff->designee->short_name == "principal"){
            $clearance->principal = true;
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
       else if(Auth::user()->hasRole("pd")){
            $per_page =$request->per_page ? $request->per_page : 10; 
            $pd_programs = Staff_PD::where('user_id',Auth::user()->id)->get('program_id');

            return response()->json([
                'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
                ->where('status', 0)->with('purpose')  
                ->with('staff')
                ->with('staff.user') 
                ->where('designee_id', 1)
                ->whereHas('student', function($query) use($pd_programs){
                    $query->whereIn('program_id', $pd_programs);
                })
                ->paginate($per_page)),
                'user' => Auth::user(), 
                
                ],200);
        }
        
        else{
        $per_page =$request->per_page ? $request->per_page : 10; 
       
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(ClearanceRequest::orderBy('request_at')
            ->where('status', 0)->with('purpose')   
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            ->with('staff')
            ->with('staff.user') 
            ->paginate(10)),
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
        $clearanceRequest = ClearanceRequest::find($id)->delete();
        return response()->json(['clearanceRequest' => $clearanceRequest],200);
    }
    public function deleteAll(Request $request)
    {
       User::whereIn('id', $request->users)->delete();
        return response()->json(['message' , 'Records Deleted Successfully'],200);
    }
}
