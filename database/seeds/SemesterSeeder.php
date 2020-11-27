<?php

use App\Clearance;
use Illuminate\Database\Seeder;
use App\ClearanceRequest;
class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClearanceRequest::orderBy('id')->update(['semester_id' => 1]);
    }
}
