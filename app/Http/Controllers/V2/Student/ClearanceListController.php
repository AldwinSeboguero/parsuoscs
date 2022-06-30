<?php

namespace App\Http\Controllers\V2\Student;

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
class ClearanceListController extends Controller
{
    public function index(Request $request){
        $per_page =$request->per_page ? $request->per_page : 10; 
        $student = Student::where('user_id', Auth::user()->id)->first();
       $clearancesProgram = Clearance::orderByDesc('id')
                                    ->where('student_id',$student->id)
                                    ->get()->map(function($inner){
                                        return [
                                            $inner->program_id
                                        ];
                                    });
        // dd($clearancesProgram);                     
        $clearances  = new ClearanceCollection(Clearance::orderByDesc('id')
                                                        ->when($request->search, function($query) use ($request,$clearancesProgram){
                                                            $query->whereHas('program',function($inner) use ($request,$clearancesProgram){
                                                                $inner->whereIn('id',$clearancesProgram)
                                                                ->where('name','ILIKE', '%' . $request->search . '%');
                                                            });
                                                        })
                                                        ->where('student_id',$student->id)
                                                        ->paginate($per_page));
        // $clearances  = tap(Clearance::orderByDesc('id')->where('student_id',$student->id)->paginate($per_page))->map(
        //     function($inner, $key) use($student){
        //         $signatoryCount = SignatoryV2::orderBy('order')
        //         ->where('campus_id', $student->program->campus_id)
        //         ->where('college_id', $student->program->college_id)
        //         ->where('program_id', $student->program_id)
        //         ->where('purpose_id', Purpose::where('purpose',json_decode($inner->purpose->purpose)->name)->get()->first()->id)
        //         ->where('semester_id',$inner->purpose->semester_id)
        //         ->get()->count();
        //         $cr = ClearanceRequestV2::where('purpose_id',$inner->purpose_id)
        //                                                 ->where('student_id',$student->id)
        //                                                 ->where('status',true)
        //                                                 ->distinct('designee_id')
        //                                                 ->get()->count();
        //         return [
        //             'key' => $key+1,
        //             'purpose' => json_decode(json_decode($inner->purpose)->purpose)->name.' '.
        //             json_decode(json_decode($inner->purpose)->purpose)->description, 
        //             'program' => $inner->program->name,
        //             'campus' =>$inner->program->campus->name,
        //             'status' => $cr >= $signatoryCount,
        //             'signatoryCount' => $signatoryCount,
        //             'ass' =>json_decode($inner->purpose->purpose)->name,
        //             'cr' =>$cr >= $signatoryCount,
        //         ];
        //         };
            
        // );
        return response()->json([
            'clearances' => $clearances,
        ]);
    

    }
}
