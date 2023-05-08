<?php

use Illuminate\Database\Seeder;
use App\Staff;
use App\Staff_DEAN;
use App\Staff_PD;
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Staff_Adviser;
use App\Semester;
use App\Graduation;
use App\SignatoryV2;
use App\ClearanceRequestV2;
class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $semester = new Semester([
        //     'id' => '4',
        //     'code' => '21-1',
        //     'semester' => 'AY 2021-2020 1st Semester',
        //     'from' => '2021-07-5',
        //     'to' => '2021-08-01',
        // ]);
        // $semester->save();

       // $graduation = new Graduation([
        //    'graduation' => 'June 2021',
       // ]);
       // $graduation->save();

       //Old
        // $staffs = Staff::orderBy('id')->where('semester_id',3)->get();
        // $staff_deans = Staff_DEAN::orderBy('id')->where('semester_id',3)->get();
        // $staff_pds = Staff_PD::orderBy('id')->where('semester_id',3)->get();
        // $staff_registrars = StaffRegistrar::orderBy('id')->where('semester_id',3)->get();
        // $staff_stcouncils = StaffStCouncil::orderBy('id')->where('semester_id',3)->get();
        // $staff_advisers = Staff_Adviser::orderBy('id')->where('semester_id',3)->get();
        // foreach ($staffs as $key => $staff) {
        //     $st = new Staff([
        //         'user_id' => $staff->user_id,
        //         'designee_id' => $staff->designee_id,
        //         'campus_id' => $staff->campus_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        // foreach ($staff_deans as $key => $staff) {
        //     $st = new Staff_DEAN([
        //         'user_id' => $staff->user_id, 
        //         'college_id' => $staff->college_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        // foreach ($staff_pds as $key => $staff) {
        //     $st = new Staff_PD([
        //         'user_id' => $staff->user_id,
        //         'program_id' => $staff->program_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        // foreach ($staff_registrars as $key => $staff) {
        //     $st = new StaffRegistrar([
        //         'user_id' => $staff->user_id,
        //         'program_id' => $staff->program_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        // foreach ($staff_stcouncils as $key => $staff) {
        //     $st = new StaffStCouncil([
        //         'user_id' => $staff->user_id, 
        //         'college_id' => $staff->college_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        // foreach ($staff_advisers as $key => $staff) {
        //     $st = new Staff_Adviser([
        //         'user_id' => $staff->user_id, 
        //         'section_id' => $staff->section_id,
        //         'semester_id' => 4,
        //     ]); 
        //     $st->save();
        // }
        $semesters = Semesters::get('id');
        $unused_signatories = SignatoryV2::whereDoesntHave('clearance_requests', function ($query) use ($semester) {
            $query->whereIn('semester_id', $semesters);
        })->get();
    
        foreach ($unused_signatories as $signatory) {
            echo $signatory->name . " is an unused signatory. <br>";
        }
        
    }
}
