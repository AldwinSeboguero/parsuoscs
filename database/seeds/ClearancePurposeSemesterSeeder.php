<?php

use Illuminate\Database\Seeder;
use App\ClearancePurpose;
class ClearancePurposeSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClearancePurpose::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
