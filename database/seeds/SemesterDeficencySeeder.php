<?php

use Illuminate\Database\Seeder;

class SemesterDeficencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Deficiency::orderBy('id')->update(['semester_id' => 1]);
    }
}
