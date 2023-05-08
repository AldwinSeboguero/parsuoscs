<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Graduation;

use App\Http\Resources\GraduationCollection;
class GraduationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['graduations'=> Graduation::orderByDesc('id')->get()],200);
    }
    
    public function getGraduations(Request $request){
        $graduation_name = Graduation::orderByDesc('id')->when($request->graduation, function ($q) use ($request){
            $q->where('id',$request->graduation);
        })->first()->graduation;
        $designation = $request->designation;
        $campus = $request->campus;
        $program = $request->program;
        $signatory = $request->signatory;
        $purpose = $request->purpose;
        $semester = $request->semester;

        $graduations =  new GraduationCollection(Graduation::orderByDesc('id')
        ->paginate($request->per_page));
        return response()->json([
            'table_data' => $graduations,
            'graduation' =>  $graduations,
            'graduation_name' => $graduation_name,
            'headers' => [
                [
                  'text'=> '#',
                  'align'=> 'start',
                  'sortable'=> false,
                  'value'=> 'id',
                //   'class'=> 'blue darken-4 white--text',
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
            
            $graduation = new Graduation([ 
            'graduation' => $request->description, 
            'from' => $request->schedules[0] <  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            'to' => $request->schedules[0] >  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1],
            ]);  
            $graduation->save(); 
            return response()->json(['graduations'=> Graduation::orderByDesc('updated_at')->paginate(10),200]);
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
        $graduation = Graduation::find($id); 
        $graduation->graduation= $request->description;  
        $graduation->from= $request->schedules[0] <  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1];
        $graduation->to= $request->schedules[0] >  $request->schedules[1] ? $request->schedules[0] :$request->schedules[1]; 
        $graduation->save();  
        return response()->json(['graduation' => $graduation],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $graduation = Graduation::find($id)->delete();
        return response()->json(['graduation' => $graduation],200);
    } 
}
