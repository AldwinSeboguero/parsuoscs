<?php

use Illuminate\Database\Seeder;

use App\Clearance;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Student;
use App\ClearancePurpose;
class ClearancePurposeIDSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearances = Clearance::all();
        foreach ($clearances as $key => $clearance) {
            if(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()){
                $clearance->purpose_id = ClearancePurpose::where('student_id',$clearance->student_id)->first()->id;
                    $clearance->save();
            
            }
        }
    }
}
