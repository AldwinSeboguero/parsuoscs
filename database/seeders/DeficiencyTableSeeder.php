<?php

use Illuminate\Database\Seeder;

use App\Deficiency;
use App\SignatoryV2;
use App\ClearanceRequestV2;
use App\Purpose;
class DeficiencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearanceRequests = Deficiency::orderBy('id')->get();
        foreach($clearanceRequests as $key  => $clearanceRequest){
            if($clearanceRequest->purpose_id){
            if(SignatoryV2::where('user_id' ,$clearanceRequest->staff->user_id)
            ->where('program_id',$clearanceRequest->student->program_id)
            ->where('semester_id', $clearanceRequest->purpose->semester_id)
            ->get()->first()){
              
                    $clearanceRequest->update([
                        'signatory_id' => SignatoryV2::where('user_id' ,$clearanceRequest->staff->user_id)
                                                        ->where('program_id',$clearanceRequest->student->program_id)
                                                        ->where('semester_id', $clearanceRequest->purpose->semester_id)
                                                        ->where('purpose_id', Purpose::where('purpose',json_decode($clearanceRequest->purpose->purpose)->name)->get()->first()->id)
                                                        ->get()->first()->id,
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
}
