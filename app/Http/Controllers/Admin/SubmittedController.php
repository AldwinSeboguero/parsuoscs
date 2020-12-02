<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\SubmitClearance; 
use App\Role; 
use App\User; 
use Illuminate\Support\Facades\Hash; 
use App\Http\Resources\SubmittedClearance as SubmittedClearanceResource;
use App\Http\Resources\SubmittedClearanceCollection;
class SubmittedController extends Controller
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
        'submittedclearances' => new SubmittedClearanceCollection(SubmitClearance::with('clearance')->with('clearance.purpose')->with('staff')->with('staff.user')->paginate($per_page)) 
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
            'submittedclearances' => new SubmittedClearanceCollection(
                SubmitClearance::with('clearance')
                ->with('clearance.purpose')
                ->with('clearance.student')  
                ->with('staff')
                ->with('staff.user') 
                ->whereHas('clearance.student', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
                ->orWhereHas('clearance.student', function($q) use ($id){
                    $q->where('student_number', 'ILIKE', '%' . $id . '%');
                })  
                ->orWhereHas('staff.user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                })  
                ->orWhereHas('clearance.purpose', function($q) use ($id){
                    $q->where('purpose', 'ILIKE', '%' . $id . '%');
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
    public function update(Request $request, $id)
    {
        

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
