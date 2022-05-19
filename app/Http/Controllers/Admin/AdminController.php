<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Clearance;
use App\Student;
use App\ClearanceRequest;
use App\UserRole;
use App\Semester;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser(){
        return response()->json([
            'user' => Auth::user(), 
            'role' => UserRole::with('role')->where('user_id',Auth::user()->id)->first(), 
            ],200);
        }
    public function index()
    {
        $semester_id = Semester::latest('id')->first();
        return response()->json([
            'semester' => $semester_id->semester,
            'completed' => Clearance::where('cashier','true')
                            ->whereAnd('library','true')
                            ->whereAnd('student_council','true')
                            ->whereAnd('osas','true')
                            ->whereAnd('program_director','true')
                            ->whereAnd('college_dean','true')
                            ->whereAnd('registrar','true')
                            ->with('purpose')   
                            ->whereHas('purpose', function($q) use ($semester_id){
                                $q->where('semester_id', $semester_id->id);
                            }) 
                            ->get()->count(),
            'totalStudent' => Student::orderBy('updated_at')->count(),
            'totalActivatedAccount' => Student::where('is_activated',true)->get()->count(),
            'pendingRequest' => ClearanceRequest::where('status',false)->get()->count(),
            'approvedRequest' => ClearanceRequest::where('status',true)
            ->with('purpose')   
            ->whereHas('purpose', function($q) use ($semester_id){
                $q->where('semester_id', $semester_id->id);
            }) 
            ->get()->count(),
            'totalClearanceRequest' => ClearanceRequest::orderBy('updated_at')
            ->with('purpose')   
            ->whereHas('purpose', function($q) use ($semester_id){
                $q->where('semester_id', $semester_id->id);
            }) 
            ->get()->count(),
                                     
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
