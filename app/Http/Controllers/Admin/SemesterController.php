<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Semester;
class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['semesters'=> Semester::orderbyDesc('code')->get()],200);
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
            
            $semester = new Semester([
            'code' => $request->code,
            'semester' => $request->semester,
            'from' => $request->from,
            'to' => $request->to,
            ]);  
            $semester->save(); 
            return response()->json(['semesters'=> Semester::orderByDesc('updated_at')->paginate(10),200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
      
 
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
        $semester = Semester::find($id);
        $semester->code= $request->code;  
        $semester->semester= $request->semester;  
        $semester->from= $request->from;  
        $semester->to= $request->to;  
        $semester->save();  
        return response()->json(['semester' => $semester],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $semester = Semester::find($id)->delete();
        return response()->json(['semester' => $semester],200);
    } 
}
