<?php

use Illuminate\Database\Seeder;
use App\StaffRegistrar;
class RegistrarsSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffRegistrar::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
