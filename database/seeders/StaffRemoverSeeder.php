<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Semester;
use App\Graduation;
use App\SignatoryV2;
use App\ClearanceRequestV2;
use App\Program;
class StaffRemoverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $semesters = Semester::get('id');
        $unused_signatories_count = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->limit(1000)
            ->pluck('id');
        $unused_signatories = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->paginate(10);
        $total = $unused_signatories->total();
        
        // ->pluck('id')->count();
        echo "Start! \n";
        echo "Total Unused: ".$total." \n";
        
        while($unused_signatories_count->count()!=0){
            $unused_signatories_count = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->limit(1000)
            ->pluck('id');
              SignatoryV2::whereIn('id', $unused_signatories_count)->delete();
                 echo $unused_signatories_count->count()." Deleted! \n";
            $total -=$unused_signatories_count->count();
            echo "Rmaining: ".$total."\n";
        }
        echo "Done! \n";
           


    }
}
