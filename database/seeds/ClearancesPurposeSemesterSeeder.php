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
        // Clearance::orderBy('updated_at')->update(['semester_id' => 1]);

        
        $clearanceRequests = ClearanceRequest::orderBy('updated_at')->update(['purpose_id' => 1]);
        // foreach ($clearanceRequests as $key => $clearanceRequest) {
        //     $clearanceRequest->purpose_id = 1;
        //      $clearanceRequest->save();
        // }
        
    // }
    }
}
