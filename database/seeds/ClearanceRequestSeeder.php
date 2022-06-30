<?php

use Illuminate\Database\Seeder;
use App\ClearanceRequest;
use App\SignatoryV2;
use App\ClearanceRequestV2;
use App\Purpose;
class ClearanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearanceRequests = ClearanceRequest::orderBy('id')->get();
        foreach($clearanceRequests as $key  => $clearanceRequest){
            if(SignatoryV2::where('user_id' ,$clearanceRequest->staff->user_id)
            ->where('program_id',$clearanceRequest->student->program_id)
            ->where('semester_id', $clearanceRequest->purpose->semester_id)
            ->get()->first()){
            ClearanceRequestV2::create([
                'token' => $clearanceRequest->token,
                'status' => $clearanceRequest->status,
                'student_id' => $clearanceRequest->student_id,
                'signatory_id' => SignatoryV2::where('user_id' ,$clearanceRequest->staff->user_id)
                                                ->where('program_id',$clearanceRequest->student->program_id)
                                                ->where('semester_id', $clearanceRequest->purpose->semester_id)
                                                ->where('purpose_id', Purpose::where('purpose',json_decode($clearanceRequest->purpose->purpose)->name)->get()->first()->id)
                                                ->get()->first()->id,
                'designee_id' => $clearanceRequest->designee_id,
                'purpose_id' => $clearanceRequest->purpose_id,
                'requested_at' => $clearanceRequest->request_at,
                'approved_at' => $clearanceRequest->approved_at,
                'updated_at' => $clearanceRequest->updated_at,
                'created_at' => $clearanceRequest->created_at,
            ]);
            echo($clearanceRequest->id);
            echo('
            ');
            echo(SignatoryV2::where('program_id',$clearanceRequest->student->program_id)
            ->where('user_id' ,$clearanceRequest->staff->user_id)
            ->where('semester_id', $clearanceRequest->purpose->semester_id)
            ->get('id')->first());
            echo('
            ');
            echo($clearanceRequest->staff->user->name.' '.$clearanceRequest->staff_id);
            echo('
            ');
        }
        }
    }
}
