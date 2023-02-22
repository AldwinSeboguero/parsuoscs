<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\StudentCompletedClearanceCollection; 
use App\Clearance;

class CompletedClearance extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $semester = $request->semester_id;

        return response()->json([
            'file_name' => $semester ? Semester::when($semester , function($q) use($semester){
                $q->where('id', $semester);
                
            })->first()->semester.'_'.'Submitted Clearances'.'_'.time().'.csv' : 'Submitted Clearances'.'_'.time().'.csv',
            'clearances' => new StudentCompletedClearanceCollection(Clearance::whereHas('purpose',function($q) use($request){
                $q->where('semester_id',8);
            })
            ->with('clearancerequest')
            // ->with('issubmitted')
            // ->submitted()

            ->has('clearancerequest')
            // ->has('issubmitted')


            ->distinct('student_id')
            ->paginate($per_page)),
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
