<?php

use Illuminate\Database\Seeder;
use App\StudentPurposeSetup;
use App\Clearance;
use App\ClearancePurpose;
class PurposeSemesterSeeder extends Seeder
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
            
            if (StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()) {
                if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->code == "enroll") {
                    if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->semester!=null) {
                    $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->semester->semester];
                    }
                    else{
                        $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => null];
                    
                    }
                }
                else if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->code == "grad") {
                    if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->graduation!=null) {
                    $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->graduation->graduation];
                    }
                    else{
                        $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => null];
                    
                    }
                }
                else if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->code == "credential") {
                    if (json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->credential!=null) {
                   
                    $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->credential];
                    }
                    else{
                        $data = ['name' => json_decode(StudentPurposeSetup::where('user_id', $clearance->student->user_id)->first()->purpose)->purpose->purpose,
                    'description' => null];
                    
                    }
                }
                $clearancePurpose = new ClearancePurpose([ 
                    'clearance_id'     => $clearance->id, 
                    'purpose' => json_encode($data),
                ]);  
                $clearancePurpose->save(); 
            }
                
           
        }
        
    }
}
