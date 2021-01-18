<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Clearance; 
use App\Role; 
use App\User; 
use Illuminate\Support\Facades\Hash; 

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Clearance as ClearanceResource;
use App\Http\Resources\ClearanceCollection;
use App\Student;
class ClearanceController extends Controller
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
        'clearances' => new ClearanceCollection(Clearance::orderBy('updated_at','desc')->with('purpose')->with('student')->with('student.program')->where('student_id' ,Student::where('user_id', Auth::user()->id)->first()->id)->paginate($per_page)) 
        ],200);
        }
        else{
            return response()->json([
            'clearances' => new ClearanceCollection(Clearance::orderBy('updated_at','desc')->with('purpose')->with('student')->with('student.program')->paginate($per_page)) 
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
