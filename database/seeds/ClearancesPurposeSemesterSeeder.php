<?php

use Illuminate\Database\Seeder;
use App\Clearance;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Student;
class ClearancesPurposeSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clearance::orderBy('updated_at')->update(['semester_id' => 1]);

        $clearances = Clearance::all();
        foreach ($clearances as $key => $clearance) {
            if(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()){
            if (json_decode(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()->purpose)->purpose->id == 1) {
                    $clearance->purpose_id = 1;
                    $clearance->save();
            }
           else if (json_decode(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()->purpose)->purpose->id == 2) {
                $clearance->purpose_id = 2;
                $clearance->save();
         }
           else if (json_decode(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()->purpose)->purpose->id == 3) {
            $clearance->purpose_id = 3;
            $clearance->save();
     }
        }
    }
    }
}
