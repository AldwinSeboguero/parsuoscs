<?php

use Illuminate\Database\Seeder;
use App\Deficiency;
use App\StudentPurposeSetup;
use App\Student;
use App\ClearancePurpose;
use App\Clearance;
class SemesterDeficencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearances = Deficiency::orderBy('id')->get();
        // App\Deficiency::orderBy('id')->update(['semester_id' => 1]);
        foreach ($clearances as $key => $clearance) {
            if(StudentPurposeSetup::where('user_id',Student::find($clearance->student_id)->user_id)->first()){
                $clearance->purpose_id = ClearancePurpose::where('student_id',$clearance->student_id)->first()->id;
                    $clearance->save();
            
     
        }
    }
}
}