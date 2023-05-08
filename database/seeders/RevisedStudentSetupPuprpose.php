<?php

use Illuminate\Database\Seeder;
use App\Clearance;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Student;
use App\ClearancePurpose;
class RevisedStudentSetupPuprpose extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setups = StudentPurposeSetup::all();
        foreach ($setups as $key => $clearance) {
             if(Student::where('user_id',$clearance->user_id)->first()){
                $clearance->purpose_id = ClearancePurpose::where('student_id',Student::where('user_id',$clearance->user_id)->first()->id)->first()->id;
                    $clearance->save();
            
             }
          
        }
    }
}
