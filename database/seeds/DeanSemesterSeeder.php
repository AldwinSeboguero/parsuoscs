<?php

use Illuminate\Database\Seeder;
use App\Staff_DEAN;
class DeanSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff_DEAN::orderBy('updated_at')->update(['semester_id' => 1]);
    }
}
