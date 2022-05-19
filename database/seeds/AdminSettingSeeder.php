<?php

use Illuminate\Database\Seeder;
use App\AdminSetting;
class AdminSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminSetting::firstOrCreate(
            [
                'name' => 'Enrollment', 
                'value' => '1', 
            ]
        );
        AdminSetting::firstOrCreate(
            [
                'name' => 'Graduation', 
                'value' => '1', 
            ]
        );
        AdminSetting::firstOrCreate(
            [
                'name' => 'Credential', 
                'value' => '1', 
            ]
        );
    }
}
