<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StaffRegistrar;
use App\User;
use App\UserRole;
use App\Designee;
use App\Staff;
use App\Campus;
use App\Semester;
use App\Staff_DEAN;  
use App\StaffStCouncil;
use App\Staff_Adviser;
use App\Program;
use App\Http\Resources\Registrar as RegistrarResource;
use App\Http\Resources\RegistrarCollection;
class RegistrarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        $registrars =  new RegistrarCollection(StaffRegistrar::orderByDesc("updated_at")->with('user')->with('semester')->with('program')->with('program.campus')
        ->paginate($per_page));
        return response()->json([
            'registrars' => $registrars,
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
                function ($q){
                    $q->where('name','!=','student');
                }
                )->get(),
                'designations' => Designee::orderBY('id')->get(),
                'programs' => Program::orderBY('id')->get(),
                'semesters' => Semester::orderByDesc('created_at')->get(),
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
        if( StaffRegistrar::where("semester_id",$request->semester_id)->whereIn('program_id', $request->program_id)->get()->count()!=0){
            StaffRegistrar::where("semester_id",$request->semester_id)->whereIn('program_id', $request->program_id)->update(['user_id' => $request->user_id]);
  
        }
       else{
           foreach($request->program_id as $pi){
           
            $st = new StaffRegistrar([
                'user_id' => $request->user_id,
                'program_id' => $pi,
                'semester_id' => $request->semester_id,
            ]); 
            $st->save();
            $st = Staff::firstOrCreate([
                'user_id' => $request->user_id,
                'designee_id' => Designee::where('short_name','registrarstaff')->first()->id,
                'campus_id' => 1,
                'semester_id' => $request->semester_id,
            ]); 
           }
           
       }

       }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'registrars' => new RegistrarCollection(
                StaffRegistrar::orderByDesc("updated_at")->with('user')->with('semester')->with('program')->with('program.campus')
                ->whereHas('user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
            ->orwhereHas('program.campus', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('program', function($q) use ($id){
                $q->where('short_name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('semester', function($q) use ($id){
                $q->where('semester', 'ILIKE', '%' . $id . '%');
            }) 
            ->paginate($per_page)),
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
                function ($q){
                    $q->where('name','!=','student');
                }
                )->get(),
                'designations' => Designee::orderBY('id')->get(),
                'programs' => Program::orderBY('id')->get(),
                'semesters' => Semester::orderByDesc('created_at')->get(),
            ],200);
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
        $staff = StaffRegistrar::find($id)->delete();
        return response()->json(['registrars' => $staff],200);
    }
}
