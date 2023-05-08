<?php

use Illuminate\Database\Seeder;
use App\ClearanceRequest;
use App\Staff;
class ClearanceRequestDesigneeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $crs = ClearanceRequest::orderBy('id')->get();

        foreach($crs as $key => $cr){
            ClearanceRequest::find($cr->id)->update([
                'designee_id' => Staff::where('id',$cr->staff_id)->get()->first()->designee_id,
                'signatory_name' => Staff::where('id',$cr->staff_id)->get()->first()->user->name,
            ]);
            printf('Staff '.$cr->staff_id.' '.$cr->staff->user->name.' '.Staff::where('id',$cr->staff_id)->get()->first()->designee_id.' '.Staff::where('id',$cr->staff_id)->get()->first()->designee->name.'.
            ');
        }
    }
}
