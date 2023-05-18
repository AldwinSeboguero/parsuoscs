<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Deficiency as DeficiencyResource;
use App\Http\Resources\DeficiencyCollection;

use Illuminate\Support\Facades\Auth;
use App\Student;
use App\Deficiency; 
use App\Staff;
use App\Semester;

use App\Clearance;
use App\ClearanceRequest;
use App\SignatoryV2;
use App\ClearanceRequestV2;
class DeficiencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
       if(Auth::user()->hasRole("student")){
            return response()->json([
            'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
            ->with('signatory.user')
            ->with('designee')
            ->with('semester')
            ->with('student')
            ->where('student_id' ,Student::where('user_id', Auth::user()->id)->first()->id)->paginate($per_page)) 
            ],200);
            }
            else if(Auth::user()->hasRole("admin")){
                return response()->json([
                    'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
                    ->with('designee')
                    ->with('semester')
                    ->with('student')
                    ->with('purpose')    
                    ->with('staff')
                    ->with('staff.user')  
                    ->paginate($per_page)) 
                    ],200);
            }
           else{
                // dd('isNotAdmin');
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
                return response()->json([
                'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
                ->orderByDesc('id')
                ->with('designee')
                ->with('semester')
                ->with('student')
                ->with('purpose')   
                ->with('signatory')
                ->with('signatory.user')  
                ->whereIn('signatory_id',$signatory_ids) 

                ->paginate($per_page)) 
                ],200);
                }
    }
    public function approve(Request $request)
    {
        if(Auth::user()->hasRole("admin")){}
        else{
        $deficiency = Deficiency::find($request->id);
        $deficiency->completed = true;
        $deficiency->save(); 
       
         
        $staffDef = Deficiency::where('student_id',$deficiency->student_id)
        ->where('purpose_id',$deficiency->purpose_id)
        ->where('signatory_id',$deficiency->signatory_id) 
        ->where('completed',0)->get();  

        if($staffDef->count() == 0 ){
            $clearance = ClearanceRequestV2::where('student_id',$deficiency->student_id)
            ->where('signatory_id',$deficiency->signatory_id)
            ->where('purpose_id',$deficiency->purpose_id)
            ->first();
            $clearance->status = 0;
            $clearance->save();
        }
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
        return response()->json([
            'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
            ->orderByDesc('id')
            ->with('designee')
            ->with('semester')
            ->with('student')
            ->with('purpose')   
            ->whereIn('signatory_id',$signatory_ids) 

           
            ->paginate(10)) 
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user()->hasRole("admin")){
            return response()->json([
                'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
                ->with('designee')
                ->with('semester')
                ->with('student')
                ->with('purpose')   
                ->whereHas('student', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
                 
                ->orWhereHas('student', function($q) use ($id){
                    $q->where('student_number', 'ILIKE', '%' . $id . '%');
                })  
                 ->with('purpose')  
                ->paginate(10)),
                ],200); 
            }
            else{
                // dd('isNotAdmin');
                $semester = Semester::orderByDesc('id')->first();
                $latest_signatory_ids = SignatoryV2::where('user_id', Auth::user()->id)
                    ->where('semester_id', $semester->id)
                    ->get('id');
                return response()->json([
                    'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
                    ->with('designee')
                    ->with('semester')
                    ->with('student')
                    ->with('purpose')   
                    ->with('signatory')
                    ->with('signatory.user')  
                    ->whereHas('student', function($q) use ($id){
                        $q->where('name', 'ILIKE', '%' . $id . '%');
                    }) 
                     
                    ->orWhereHas('student', function($q) use ($id){
                        $q->where('student_number', 'ILIKE', '%' . $id . '%');
                    })  
                     ->whereIn('signatory_id',$latest_signatory_ids)->get()
                    ->paginate(10)),
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
    public function update(Request $request)
    {
        // dd($request);
        Deficiency::find($request->id)->update([
            'title' => $request->deficiency,
            'note' => $request->note,
            'updated_at' => now(),
        ]);
        return $this->index($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
