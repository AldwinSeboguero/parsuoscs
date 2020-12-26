<?php

use Illuminate\Database\Seeder;
use App\Clearance;
use App\ClearanceRequest;
use App\StudentPurposeSetup;
use App\Student;
use App\ClearancePurpose;
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
                $clearance->purpose_id = ClearancePurpose::where('clearance_id',$clearance->id)->first()->id;
                    $clearance->save();
            
     
        }
    }
    }
}
