<?php

namespace App\Http\Controllers\V2\Signatory;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request; 
use App\ClearanceRequest; 
use App\Role; 
use App\User; 
use App\Student; 
use App\ClearancePurpose;
use App\Deficiency; 
use App\Staff;
use App\Clearance;
use App\Staff_PD;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Pagination\Paginator;
use App\Http\Resources\ClearanceRequest as ClearanceRequestResource;
use App\Http\Resources\ClearanceRequestCollection; 
use Illuminate\Pagination\LengthAwarePaginator;
use App\Support\Collection;
use App\StudentPurposeSetup;
use DB;
use Illuminate\Support\Facades\Auth;
use App\ClearanceRequestV2;
use App\SignatoryV2;

class ClearanceRequestController extends Controller
{   
        public function index(Request $request){
            $per_page =$request->per_page ? $request->per_page : 10; 
            $signatory_ids = SignatoryV2::where('user_id',Auth::user()->id)->get('id');

            $clearance_requests =new ClearanceRequestCollection( ClearanceRequestV2::orderBy('requested_at')
                                                    ->when($request->search, function($inner) use($request){
                                                        $inner->whereHas('student', function($q) use ($request){
                                                            $q->where('name', 'ILIKE', '%' . $request->search . '%');
                                                        }) 
                                                         
                                                        ->orWhereHas('student', function($q) use ($request){
                                                            $q->where('student_number', 'ILIKE', '%' . $request->search . '%');
                                                        });
                                                    })
                                                    ->whereIn('signatory_id', $signatory_ids)
                                                    ->where('status', false)
                                                    ->paginate($per_page));

            return response()->json([
                'signatory' => $signatory_ids->count(),
                'clearance_requests' => $clearance_requests,
            ]);
        }
        public function approve(Request $request){
        $clearanceRequest = ClearanceRequestV2::find($request->id);
        $clearanceRequest->status = true;
        $clearanceRequest->approved_at = now();
        $clearanceRequest->save();
        return $this->index($request);

        
        
        }
        public function disapprove(Request $request){
            $clearanceRequest = ClearanceRequestV2::find($request->requestId);
            $student = Student::find($clearanceRequest->student_id);
            $d = Deficiency::create([
            'title' => $request->title,   
            'note' => $request->note,   
            'signatory_id' => $clearanceRequest->signatory_id,   
            'designee_id' => $clearanceRequest->signatory->designee_id,   
            'student_id' => $clearanceRequest->student_id, 
            'semester_id' => $clearanceRequest->purpose->semester_id, 
            'purpose_id' => $clearanceRequest->purpose_id,    
            ]); 
            $student->deficiencies()->attach($d);
        //update request status
            $clearanceRequest->status = 2;
            $clearanceRequest->save();
        
        
        return $this->index($request);
        
        }

}