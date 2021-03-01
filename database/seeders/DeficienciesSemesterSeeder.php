<?php

use Illuminate\Database\Seeder;
use App\Deficiency;
class DeficienciesSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Deficiency::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
