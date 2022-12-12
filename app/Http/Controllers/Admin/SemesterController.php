<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Semester;
use App\Http\Resources\SemesterCollection;

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

    public function getSemesters(Request $request){
        $semester_name = Semester::orderByDesc('id')->when($request->semester, function ($q) use ($request){
            $q->where('id',$request->semester);
        })->first()->semester;
        $designation = $request->designation;
        $campus = $request->campus;
        $program = $request->program;
        $signatory = $request->signatory;
        $purpose = $request->purpose;
        $semester = $request->semester;

        $semesters =  new SemesterCollection(Semester::orderByDesc('id')
        ->paginate($request->per_page));
        return response()->json([
            'table_data' => $semesters,
            'semester' =>  $semester,
            'semester_name' => $semester_name,
            'headers' => [
                [
                  'text'=> '#',
                  'align'=> 'start',
                  'sortable'=> false,
                  'value'=> 'id',
                //   'class'=> 'blue darken-4 white--text',
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Code', 
                  'sortable'=> false,
                  'value'=> 'code',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Description', 
                  'sortable'=> false,
                  'value'=> 'description',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Schedule', 
                  'sortable'=> false,
                  'value'=> 'schedule',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                  [ 'text'=> '', 
                  'sortable'=> false,
                  'value'=> 'actions',
                //   'class'=> 'blue darken-4 white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                  ]
              ],
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $semester = new Semester([
            'code' => $request->code,
            'semester' => $request->description,
            'from' => $request->schedules[0] <  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            'to' => $request->schedules[0] >  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            ]);  
            $semester->save(); 
            $semesters =  new SemesterCollection(Semester::orderByDesc('id')
            ->paginate($request->per_page));
            return response()->json([
                'table_data' => $semesters,]);
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
            'semester' => $request->description,
            'from' => $request->schedules[0] <  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            'to' => $request->schedules[0] >  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            ]);  
            $semester->save(); 
            $semesters =  new SemesterCollection(Semester::orderByDesc('id')
            ->paginate($request->per_page));
            return response()->json([
                'table_data' => $semesters,]);
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
        $semester->semester= $request->description;  
        $semester->from= $request->schedules[0] <  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1];
        $semester->to= $request->schedules[0] >  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1];
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
