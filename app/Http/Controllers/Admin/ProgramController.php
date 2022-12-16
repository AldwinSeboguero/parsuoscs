<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Program;
use App\Campus;
use App\College;
use App\Semester;

use App\Http\Resources\ProgramCollection;

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
                'programs'=> Program::when($request->campus, function($q) use($request){
                    $q->where('campus_id',$request->campus);
                })
                ->when($request->college, function($q) use($request){
                    $q->where('college_id',$request->college);
                })
                ->get(),
                'campuses' => Campus::orderBy('name')->get(),
                'colleges' => College::orderBy('name')->get(),
              ],200);
    }
    public function getPrograms(Request $request){
        $semester_name = Semester::orderByDesc('id')->when($request->semester, function ($q) use ($request){
            $q->where('id',$request->semester);
        })->first()->semester;
        $code = $request->code;
        $description = $request->description;
        $college_id = $request->college_id;
        $campus_id = $request->campus_id;

        $programs =  new ProgramCollection(Program::orderByDesc('updated_at')
        ->when($code, function ($q) use ($code){
            $q->where('short_name','ilike','%'.$code.'%');
        })
        ->when($description, function ($q) use ($description){
            $q->where('name','ilike','%'.$description.'%');
        })
        ->when($campus_id, function ($q) use ($campus_id){
            $q->where('campus_id',$campus_id);
        })
        ->when($college_id, function ($q) use ($college_id){
            $q->where('college_id',$college_id);
        })
        ->paginate($request->per_page));
        return response()->json([
            'table_data' => $programs,
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
                [ 'text'=> 'College', 
                  'sortable'=> false,
                  'value'=> 'college',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Campus', 
                  'sortable'=> false,
                  'value'=> 'campus',
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
            $program = new Program([
            'name' => $request->description,
            'short_name' => $request->code,
            'campus_id'=> $request->campus_id, 
            'college_id'=> $request->college_id, 
            ]);  
            $program->save(); 
            return response()->json(['programs'=> Program::orderByDesc('updated_at')->paginate(10),200]);
    }
    public function store(Request $request)
    { 
            
            $program = new Program([
            'name' => $request->description,
            'short_name' => $request->code,
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
        $program->name= $request->description;  
        $program->short_name= $request->code;  
        $program->campus_id= $request->campus_id;  
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
