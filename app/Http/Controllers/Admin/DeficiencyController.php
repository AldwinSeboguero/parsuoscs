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
use App\Clearance;
use App\ClearanceRequest;
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
            ->with('staff.user')
            ->with('designee')
            ->with('semester')
            ->with('student')
            ->where('student_id' ,Student::where('user_id', Auth::user()->id)->first()->id)->paginate($per_page)) 
            ],200);
            }
           else{
                return response()->json([
                'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
                ->with('designee')
                ->with('semester')
                ->with('student')
                ->with('purpose')   
                ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
                ->with('staff')
                ->with('staff.user')  
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
        ->where('staff_id',$deficiency->staff_id) 
        ->where('completed',0)->get();  

        if($staffDef->count() == 0 ){
            $clearance = ClearanceRequest::where('student_id',$deficiency->student_id)
            ->where('staff_id',$deficiency->staff_id)
            ->where('purpose_id',$deficiency->purpose_id)
            ->first();
            $clearance->status = 0;
            $clearance->save();
        }
        return response()->json([
            'deficiencies' => new DeficiencyCollection(Deficiency::orderBy('completed')
            ->with('designee')
            ->with('semester')
            ->with('student')
            ->with('purpose')   
            ->whereIn('staff_id',Staff::where('user_id',Auth::user()->id)->get('id')) 
            ->with('staff')
            ->with('staff.user')  
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
        //
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
        //
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
