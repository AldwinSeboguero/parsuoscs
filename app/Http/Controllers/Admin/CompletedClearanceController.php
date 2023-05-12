<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\ClearanceRequest;
use App\Semester; 
use App\Role; 
use App\User; 
use App\Student; 
use App\ClearancePurpose;
use App\Deficiency; 
use App\Staff;
use App\Clearance;
use App\ClearanceRequestV2;
use App\SignatoryV2;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Pagination\Paginator;
use App\Http\Resources\ClearanceRequest as ClearanceRequestResource;
use App\Http\Resources\ClearanceRequestCollection; 
use Illuminate\Pagination\LengthAwarePaginator;
use App\Support\Collection;
use App\StudentPurposeSetup;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Staff_PD;
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
        $semester = $request->semester;
        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10; 
            // $signatory_ids = SignatoryV2::where('user_id',Auth::user()->id)->get('id');
            
            $clearance_requests =new ClearanceRequestCollection( ClearanceRequestV2::orderByDesc('approved_at')
                                                    ->where('status', true)
                                                    ->when($request->student, function($inner) use($request){
                                                        $inner->whereHas('student', function($q) use ($request){
                                                            $q->where('name', 'ILIKE', '%' . $request->student . '%')
                                                            ->orWhere('student_number', 'ILIKE', '%' . $request->student . '%');
                                                        });
                                                    })
                                                    ->when($request->semester, function($inner) use($request){
                                                        $inner->whereHas('purpose',function($q) use($request){
                                                            $q->where('semester_id',$request->semester);
                                                        });
                                                    }) 
                                                    ->when($request->purpose, function($inner) use($request){
                                                        $inner->whereHas('purpose',function($q) use($request){
                                                            $q->where('',$request->purpose);
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
                                                    ->paginate($per_page));

            return response()->json([
                // 'signatory' => $signatory_ids->count(),
                'clearance_requests' => $clearance_requests,
                'file_name' => $semester ? Semester::when($semester , function($q) use($semester){
                    $q->where('id', $semester);
                    
                })->first()->semester.'_'.'Aprroved Clearances'.'_'.time().'.csv' : 'Approved Clearances'.'_'.time().'.csv',
            ]);
        }
         else if(Auth::user()->hasRole("pd")){
            $per_page =$request->per_page ? $request->per_page : 10; 
            $semester = Semester::orderByDesc('id')->first();
            $latest_signatory_ids = SignatoryV2::where('user_id', Auth::user()->id)
                ->where('semester_id', $semester->id)
                ->get(['program_id', 'designee_id', 'semester_id','campus_id','purpose_id'])
                ->toArray();
            // dd(SignatoryV2::where('user_id', Auth::user()->id)
            // ->where('semester_id', $semester->id)
            // ->get(['program_id', 'designee_id', 'semester_id','campus_id']));
            $program_ids = array_column($latest_signatory_ids, 'program_id');
            $designee_ids = array_column($latest_signatory_ids, 'designee_id');
            $campus_ids = array_column($latest_signatory_ids, 'campus_id');
            $purpose_ids = array_column($latest_signatory_ids, 'purpose_id');


            $signatory_ids = SignatoryV2::whereIn('program_id', $program_ids)->whereIn('designee_id', $designee_ids)->whereIn('campus_id', $campus_ids)
            // ->whereIn('purpose_id', $purpose_ids)
                ->get(['id']);
            $programs = SignatoryV2::where('user_id', Auth::user()->id)->distinct('campus_id')->get('campus_id');

            $clearance_requests =new ClearanceRequestCollection( ClearanceRequestV2::orderByDesc('approved_at')
            ->whereIn('signatory_id', $signatory_ids)
            ->where('status', true)
            ->when($request->student, function($inner) use($request){
                $inner->whereHas('student', function($q) use ($request){
                    $q->where('name', 'ILIKE', '%' . $request->student . '%')
                    ->orWhere('student_number', 'ILIKE', '%' . $request->student . '%');
                });
            })
            ->when($request->semester, function($inner) use($request){
                $inner->whereHas('purpose',function($q) use($request){
                    $q->where('semester_id',$request->semester);
                });
            }) 
            ->when($request->purpose, function($inner) use($request){
                $inner->whereHas('purpose',function($q) use($request){
                    $q->where('',$request->purpose);
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
            ->whereIn('signatory_id', $signatory_ids)
            ->where('status', true)
            ->paginate($per_page));

            return response()->json([
            // 'signatory' => $signatory_ids->count(),
            'clearance_requests' => $clearance_requests,
            ]);
        }
        else{
            $per_page =$request->per_page ? $request->per_page : 10; 
            $semester = Semester::orderByDesc('id')->first();
            $latest_signatory_ids = SignatoryV2::where('user_id', Auth::user()->id)
                ->where('semester_id', $semester->id)
                ->get(['program_id', 'designee_id', 'semester_id','campus_id','purpose_id'])
                ->toArray();
            // dd(SignatoryV2::where('user_id', Auth::user()->id)
            // ->where('semester_id', $semester->id)
            // ->get(['program_id', 'designee_id', 'semester_id','campus_id']));
            $program_ids = array_column($latest_signatory_ids, 'program_id');
            $designee_ids = array_column($latest_signatory_ids, 'designee_id');
            $campus_ids = array_column($latest_signatory_ids, 'campus_id');
            $purpose_ids = array_column($latest_signatory_ids, 'purpose_id');


            $signatory_ids = SignatoryV2::whereIn('program_id', $program_ids)->whereIn('designee_id', $designee_ids)->whereIn('campus_id', $campus_ids)
            // ->whereIn('purpose_id', $purpose_ids)
                ->get(['id']);
            
            $clearance_requests =new ClearanceRequestCollection( ClearanceRequestV2::orderByDesc('approved_at')
            ->whereIn('signatory_id', $signatory_ids)
            ->where('status', true)
            ->when($request->student, function($inner) use($request){
                $inner->whereHas('student', function($q) use ($request){
                    $q->where('name', 'ILIKE', '%' . $request->student . '%')
                    ->orWhere('student_number', 'ILIKE', '%' . $request->student . '%');
                });
            })
            ->when($request->semester, function($inner) use($request){
                $inner->whereHas('purpose',function($q) use($request){
                    $q->where('semester_id',$request->semester);
                });
            }) 
            ->when($request->purpose, function($inner) use($request){
                $inner->whereHas('purpose',function($q) use($request){
                    $q->where('',$request->purpose);
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
            ->paginate($per_page));

            return response()->json([
            // 'signatory' => $signatory_ids->count(),
            'clearance_requests' => $clearance_requests,
            ]);
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
    public function show(Request $request)
    {
        $id = $request->id;
        $per_page =$request->per_page ? $request->per_page : 10; 
        $signatory_ids = SignatoryV2::where('user_id',Auth::user()->id)->get('id');
        if(Auth::user()->hasRole("admin")){
            $per_page =$request->per_page ? $request->per_page : 10; 
         
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(
                ClearanceRequestV2::orderByDesc('approved_at')
                                                    
                                                    ->whereHas('student', function($q) use ($id){
                                                        $q->where('name', 'ILIKE', '%' . $id . '%')
                                                        ->orWhere('student_number', 'ILIKE', '%' . $id . '%');
                                                    }) 
                                                    ->where('status', true)
                                                    ->paginate($per_page)),
               
            'user' => Auth::user(), 
            ],200); 
        }
        else{
        $per_page =$request->per_page ? $request->per_page : 10; 
         
        return response()->json([
            'clearancerequests' => new ClearanceRequestCollection(
                ClearanceRequestV2::orderByDesc('approved_at')
                                                    
                                                    ->whereHas('student', function($q) use ($id){
                                                        $q->where('name', 'ILIKE', '%' . $id . '%')
                                                        ->orWhere('student_number', 'ILIKE', '%' . $id . '%');
                                                    }) 
                                                    ->whereIn('signatory_id', $signatory_ids)
                                                    ->where('status', true)
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
