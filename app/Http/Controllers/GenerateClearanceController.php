<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GenerateClearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', Auth::user()->id)->first()->purpose)->purpose->purpose,
        'description' => json_decode(StudentPurposeSetup::where('user_id', Auth::user()->id)->first()->purpose)->semester->semester];
        
            $clearancePurpose = new ClearancePurpose([ 
                'student_id'     => Student::where('user_id',Auth::user()->id)->first()->id, 
                'purpose' => json_encode($data),
            ]);  
            $clearancePurpose->save();
        $exist = Clearance::where('student_id',Student::where('user_id',Auth::user()->id)->first()->id)->first();
                if(!$exist){
                    $user = Clearance::updateOrCreate([
                        'student_id' => $student->id, 
                        'purpose_id' => ClearancePurpose::where(Student::where('user_id',Auth::user()->id)->first()->id)->first()->id,
                    ]);
        }
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
