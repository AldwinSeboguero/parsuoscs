<?php

use Illuminate\Database\Seeder;
use App\Staff;
use App\Staff_DEAN;
use App\Staff_PD;
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Staff_Adviser;
class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staffs = Staff::orderBy('id')->get();
        $staff_deans = Staff_DEAN::orderBy('id')->get();
        $staff_pds = Staff_PD::orderBy('id')->get();
        $staff_registrars = StaffRegistrar::orderBy('id')->get();
        $staff_stcouncils = StaffStCouncil::orderBy('id')->get();
        $staff_advisers = Staff_Adviser::orderBy('id')->get();
        foreach ($staffs as $key => $staff) {
            $st = new Staff([
                'user_id' => $staff->user_id,
                'designee_id' => $staff->designee_id,
                'campus_id' => $staff->campus_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
        foreach ($staff_deans as $key => $staff) {
            $st = new Staff_DEAN([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
        foreach ($staff_pds as $key => $staff) {
            $st = new Staff_PD([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
        foreach ($staff_registrars as $key => $staff) {
            $st = new StaffRegistrar([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
        foreach ($staff_stcouncils as $key => $staff) {
            $st = new StaffStCouncil([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
        foreach ($staff_advisers as $key => $staff) {
            $st = new Staff_Adviser([
                'user_id' => $staff->user_id, 
                'section_id' => $staff->section_id,
                'semester_id' => 2,
            ]); 
            $st->save();
        }
    }
}
