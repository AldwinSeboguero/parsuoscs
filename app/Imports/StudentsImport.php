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
use App\Semester;
use App\User;
use App\Role;
use App\UserRole;

use Illuminate\Support\Facades\Hash;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $isAccountExits = User::where('email',$row['email'])->get()->count();
        if($isAccountExits == 0){
            $role = Role::where('description','Student')->first();
            $user = User::create([
                'name' => $row['last_name'].', '.$row['first_name'],
                'email' => $row['email'],
                'password' => Hash::make($row['student_id']), 
                'username' => $row['email'],
            ]);
            $user->save();
            $user->roles()->attach($role);
            $userRole = UserRole::where('user_id',$user->id)->where('role_id',$role->id)->first();

            $student = Student::updateOrCreate([
                'student_number'    => $row['student_id'],
                'slug'    => str_replace(".","",str_replace(",","",str_replace(" ","", $row['campus']))).'-'.$row['student_id'].'-'.str_replace(".","",str_replace(",","",str_replace(" ","", $row['last_name'].', '.$row['first_name']))),
                'name'    => $row['last_name'].', '.$row['first_name'],
                'year'    => 1,
                'section_id'     => 1, 
                'program_id'     => Program::where('name',$row['program'])->where('campus_id',Campus::where('name',$row['campus'])->first()->id)->first()->id, 
                'initial_password' => Str::uuid()->toString(),
                'semester_id' => Semester::latest('id')->first()->id,
                'user_id' => $user->id,
    
            ]);
           
            return $student;
        }
        
    }
}
