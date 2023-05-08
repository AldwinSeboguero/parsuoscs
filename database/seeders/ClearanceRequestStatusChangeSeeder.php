<?php

use Illuminate\Database\Seeder;
use App\ClearanceRequest;
use App\Student;
class ClearanceRequestStatusChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clearances = ClearanceRequest::orderBy('updated_at')->get();
        foreach ($clearances as $clearance) {
            if ($clearance->student->deficiencies->where('completed',false)->where('staff_id',$clearance->staff_id)->count()) {
                $clearance->status = 2;
                $clearance->save();
            }
            
     
        }
     

        // dd(ClearanceRequest::where('student_id',3370)->first()->student->deficiencies->where('completed',0)->count());
    }
}
