<?php

use Illuminate\Database\Seeder;
use App\Staff_PD;
class PDSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff_PD::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
