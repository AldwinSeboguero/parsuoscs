<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

use App\Semester;
use App\Graduation;
use App\SignatoryV2;
use App\ClearanceRequestV2;
class RemoveStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $semesters = Semester::get('id');
        $unused_signatories = SignatoryV2::whereDoesntHave('clearance_requests', function ($query) use ($semester) {
            $query->whereIn('semester_id', $semesters);
        })->get();
    
        foreach ($unused_signatories as $signatory) {
            echo $signatory->name . " is an unused signatory. <br>";
        }
    }
}
