<?php

use Illuminate\Database\Seeder;
use App\Staff;
class StaffSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
