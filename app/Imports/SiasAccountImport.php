<?php

namespace App\Imports;

use App\Student;
use App\Section;
use Maatwebsite\Excel\Concerns\ToModel;  
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Program;
use App\ProgramStudent;
use App\Campus;
use Illuminate\Support\Str;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

use App\SiasAccount;
class SiasAccountImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $SiasAccount = SiasAccount::updateOrCreate([ 
            'student_id'    => $row['student_id'],
            'user_id'    => $row['user_id'],
            'password'    => $row['password'],
             

        ]);
       
        return $SiasAccount;
    }
}
