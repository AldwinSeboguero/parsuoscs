<?php

use Illuminate\Database\Seeder;
use App\Staff_Adviser;
class AdviserSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff_Adviser::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
