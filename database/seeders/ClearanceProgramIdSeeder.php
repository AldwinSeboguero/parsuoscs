<?php

use Illuminate\Database\Seeder;
use App\Student;
use App\Clearance;
class ClearanceProgramIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = Student::orderBy('id')->get();
        echo($students->count());
        echo('
            ');
        foreach($students as $key  => $student){
            Clearance::where('student_id', $student->id)->update([
                'program_id' => $student->program_id,
            ]);
            echo($student->id);
            echo('
                ');
        }
       
        
    }
}
