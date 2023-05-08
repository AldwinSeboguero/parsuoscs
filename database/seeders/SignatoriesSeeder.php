<?php

use Illuminate\Database\Seeder;
use App\SignatoryV2;
use App\Staff;
use App\Staff_PD;
use App\Staff_DEAN;
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Program;
use App\Purpose;

class SignatoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $purposes = Purpose::orderBy('id')->get();
    foreach($purposes as $key => $purpose){

       $oldSgnatories = Staff_PD::orderBy('id')->get();
       foreach($oldSgnatories as $key => $signatory){
            SignatoryV2::create(
                [
                // 'id' => $signatory->id,
                'program_id' => $signatory->program_id,
                'campus_id' => $signatory->program->campus_id,
                'college_id' => $signatory->program->college_id,
                'user_id' => $signatory->user_id,
                'name' => $signatory->user->name,
                'designee_id' => 1,
                'semester_id' => $signatory->semester_id,
                'order' => 5,
                'purpose_id' => $purpose->id,
                'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                'created_at' => $signatory->created_at ? $signatory->created_at : '',
                ]
            
            );
            echo($signatory->id);
       };
       $oldSgnatories = StaffRegistrar::orderBy('id')->get();
            foreach($oldSgnatories as $key => $signatory){
                SignatoryV2::create(
                    [
                    // 'id' => $signatory->id,
                    'program_id' => $signatory->program_id,
                    'campus_id' => $signatory->program->campus_id,
                    'college_id' => $signatory->program->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 9,
                    'order' => 7,
                    'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            };

        
       $programs = Program::orderBy('id')->get();
       foreach($programs as $key => $program){

            $oldSgnatories = Staff_DEAN::orderBy('id')->get();
            foreach($oldSgnatories as $key => $signatory){
                    SignatoryV2::create(
                        [
                        // 'id' => $signatory->id,
                        'program_id' => $program->id,
                        'campus_id' => $signatory->college->campus_id,
                        'college_id' => $signatory->college_id,
                        'user_id' => $signatory->user_id,
                        'name' => $signatory->user->name,
                        'designee_id' => 2,
                        'order' => 6,
                        'purpose_id' => $purpose->id,
                        'semester_id' => $signatory->semester_id,
                        'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                        'created_at' => $signatory->created_at ? $signatory->created_at : '',
                        ]
                    
                    );
                    echo($signatory->id);
            };

            

            $oldSgnatories = StaffStCouncil::orderBy('id')->get();
            foreach($oldSgnatories as $key => $signatory){
                SignatoryV2::create(
                    [
                    // 'id' => $signatory->id,
                    'program_id' => $program->id,
                    'campus_id' => $signatory->college->campus_id,
                    'college_id' => $signatory->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 8,
                    'order' => 1,
                    'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            };

            //Cashier
            $oldSgnatories = Staff::orderBy('id')->where('designee_id', 3)->get();
            foreach($oldSgnatories as $key => $signatory){
                SignatoryV2::create(
                    [
                    // 'id' => $signatory->id,
                    'program_id' => $program->id,
                    'campus_id' => $signatory->campus_id,
                     'college_id' => $program->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 3,
                    'order' => 2,
                'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            };
            //Library
            $oldSgnatories = Staff::orderBy('id')->where('designee_id', 5)->get();
            foreach($oldSgnatories as $key => $signatory){
                SignatoryV2::create(
                    [
                    // 'id' => $signatory->id,
                    'program_id' => $program->id,
                    'campus_id' => $signatory->campus_id,
                     'college_id' => $program->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 5,
                    'order' => 3,
                'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            };

            //OSAS
            $oldSgnatories = Staff::orderBy('id')->where('designee_id', 6)->get();
            foreach($oldSgnatories as $key => $signatory){
                SignatoryV2::create(
                    [
                        'program_id' => $program->id,
                        'campus_id' => $signatory->campus_id,
                         'college_id' => $program->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 6,
                    'order' => 4,
                'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            };
                
            //Registrar
            $oldSgnatories = Staff::orderBy('id')->where('designee_id', 4)->get();
            foreach($oldSgnatories as $key => $signatory){
                if($signatory->campus_id !=1){
                SignatoryV2::create(
                    [
                    'program_id' => $program->id,
                    'campus_id' => $signatory->campus_id,
                     'college_id' => $program->college_id,
                    'user_id' => $signatory->user_id,
                    'name' => $signatory->user->name,
                    'designee_id' => 4,
                    'order' => 7,
                'purpose_id' => $purpose->id,
                    'semester_id' => $signatory->semester_id,
                    'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                    'created_at' => $signatory->created_at ? $signatory->created_at : '',
                    ]
                
                );
                echo($signatory->id);
            }
            };
    }
}
    }
}
