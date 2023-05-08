<?php

use Illuminate\Database\Seeder;
use App\StaffStCouncil;
class CouncilSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffStCouncil::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
