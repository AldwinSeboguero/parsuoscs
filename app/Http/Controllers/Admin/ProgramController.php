<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Program;
use App\Campus;
use App\College;
class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            [
                'programs'=> Program::with('campus')->with('college')->get(),
                'campuses' => Campus::orderBy('name')->get(),
                'colleges' => College::orderBy('name')->get(),
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
    public function store(Request $request)
    { 
            
            $program = new Program([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'campus_id'=> $request->campus_id, 
            'college_id'=> $request->college_id, 
            ]);  
            $program->save(); 
            return response()->json(['programs'=> Program::orderByDesc('updated_at')->paginate(10),200]);
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
        $program = Program::find($id);
        $program->name= $request->name;  
        $program->short_name= $request->short_name;  
        $program->college_id= $request->college_id;  
        $program->save();  
        return response()->json(['program' => $program],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $program = Program::find($id)->delete();
        return response()->json(['program' => $program],200);
    } 
}
