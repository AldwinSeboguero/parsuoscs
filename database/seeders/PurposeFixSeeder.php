<?php

use Illuminate\Database\Seeder;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Student;
use App\ClearancePurpose;
use App\Clearance;
class PurposeFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearances = ClearanceRequest::orderBy('id')->get();
    //     foreach ($clearances as $key => $clearance) {
    //         if (json_decode(StudentPurposeSetup::find($clearance->purpose_id)->purpose)->purpose->id == 1) {
    //                 $clearance->purpose_id = 1;
    //                 $clearance->save();
    //         }
    //        else if (json_decode(StudentPurposeSetup::find($clearance->purpose_id)->purpose)->purpose->id == 2) {
    //             $clearance->purpose_id = 2;
    //             $clearance->save();
    //      }
    //      else if (json_decode(StudentPurposeSetup::find($clearance->purpose_id)->purpose)->purpose->id == 3) {
    //         $clearance->purpose_id = 3;
    //         $clearance->save();
    //  }
    //     }

        foreach ($clearances as $key => $clearance) {
            if(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()){
                $clearance->purpose_id = ClearancePurpose::where('student_id',$clearance->student_id)->first()->id;
                    $clearance->save();
            
     
        }
    }
    }
}
