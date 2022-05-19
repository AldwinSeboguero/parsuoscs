<?php

namespace App\Exports;

use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\RemembersRowNumber; 
use App\Semester;
class StudentActivationCode implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    use RemembersRowNumber;
    private  $campus_id;
    private  $program_id; 
    public function __construct( $campus_id, $program_id)
    {
      $this->campus_id = $campus_id;
      $this->program_id = $program_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       
        //  dd($this->program_id);
        if($this->program_id!=null){
        return Student::with('program')->with('program.campus')->orderBy('created_at')
 
        ->where('user_id', null)
        ->where('program_id' , $this->program_id)
        ->whereHas('program.campus', function($q){
            $q->where('id', $this->campus_id);
        }) 
        ->get();
        }
        else if($this->program_id==null && $this->campus_id!=null){
            return Student::with('program')->with('program.campus')->orderBy('created_at')

            ->where('user_id', null)
            ->whereHas('program.campus', function($q){
                $q->where('id', $this->campus_id);
            }) 

            ->get();
        }
        else{
            return Student::with('program')->with('program.campus')->orderBy('created_at')->where('user_id', null)

            ->get();
        }
    }
    public function map($student) : array
    {
        
        
        return [
          
            $student->student_number,
            $student->name,
            $student->program->name,
            $student->program->campus->name,
            $student->initial_password,

        ];
    }
    public function headings() : array
    {
        return [
            
            'Student Id',
            'Name',
            'Program',
            'Campus',
            'Activation Code',

        ];
    }
}
