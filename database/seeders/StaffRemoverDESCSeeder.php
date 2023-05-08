<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Semester;
use App\Graduation;
use App\SignatoryV2;
use App\ClearanceRequestV2;
use App\Program;
class StaffRemoverDESCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $semesters = Semester::get('id');
        $unused_signatories_count = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->whereIn('purpose_id',[1,2,3])->limit(1000)
            ->pluck('id');
        $unused_signatories = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->whereIn('purpose_id',[1,2,3])->paginate(10);
        $total = $unused_signatories->total();
        
        // ->pluck('id')->count();
        echo "Start! \n";
        echo "Total Unused: ".$total." \n";
        
        while($unused_signatories_count->count()!=0){
            $unused_signatories_count = SignatoryV2::orderByDesc('id')->whereDoesntHave('clearance_requests')->whereIn('semester_id', [1,2,3,4,5,6,7])->whereIn('purpose_id',[1,2,3])->limit(1000)
            ->pluck('id');
              SignatoryV2::whereIn('id', $unused_signatories_count)->delete();
                 echo $unused_signatories_count->count()." Deleted! \n";
            $total -=$unused_signatories_count->count();
            echo "Rmaining: ".$total."\n";
        }
        echo "Done! \n";

        // foreach ($unused_signatories as $signatory) {
        //     echo $signatory->clearance_requests->count()."\n";
        //     echo $signatory->name . " is an unused signatory. ".Program::where('id',$signatory->program_id)->get()->first()->code."\n";
        //     $staff = SignatoryV2::find($signatory->id)->delete();
        //     echo "Deleted! \n";
        // }
           


    }
}
