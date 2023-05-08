<?php

use Illuminate\Database\Seeder;
use App\SignatoryV2;
use App\Semester;
class NewSignatorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prevsem_id = Semester::orderByDesc('id')->skip(1)->take(1)->get()->first()->id;
        $newsem_id = Semester::orderByDesc('id')->take(1)->get()->first()->id;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [3,6,5,2])
                    ->where('purpose_id',1)
                    ->where('semester_id',$prevsem_id)
                    ->get();
                    foreach($signatories as $key => $signatory){

                        echo('
                        ');
                        echo($signatory->name);
                        SignatoryV2::create(
                            [
                            'program_id' => $signatory->program_id,
                            'campus_id' => $signatory->campus_id,
                            'college_id' => $signatory->college_id,
                            'user_id' => $signatory->user_id,
                            'name' => $signatory->name,
                            'designee_id' => $signatory->designee_id,
                            'order' => $signatory->order,
                            'purpose_id' => $signatory->purpose_id,
                            'semester_id' => $newsem_id,
                            'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                            'created_at' => $signatory->created_at ? $signatory->created_at : '',
                            ]
                        
                        );
                    }
                    $signatories = SignatoryV2::orderBy('designee_id')
                    ->where('purpose_id',2)
                    ->where('semester_id',$prevsem_id)
                    ->get();
                    foreach($signatories as $key => $signatory){

                        echo('
                        ');
                        echo($signatory->name);
                        SignatoryV2::create(
                            [
                            'program_id' => $signatory->program_id,
                            'campus_id' => $signatory->campus_id,
                            'college_id' => $signatory->college_id,
                            'user_id' => $signatory->user_id,
                            'name' => $signatory->name,
                            'designee_id' => $signatory->designee_id,
                            'order' => $signatory->order,
                            'purpose_id' => $signatory->purpose_id,
                            'semester_id' => $newsem_id,
                            'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                            'created_at' => $signatory->created_at ? $signatory->created_at : '',
                            ]
                        
                        );
                    }
                    $signatories = SignatoryV2::orderBy('designee_id')
                    ->where('purpose_id',3)
                    ->where('semester_id',$prevsem_id)
                    ->get();
                    foreach($signatories as $key => $signatory){

                        echo('
                        ');
                        echo($signatory->name);
                        SignatoryV2::create(
                            [
                            'program_id' => $signatory->program_id,
                            'campus_id' => $signatory->campus_id,
                            'college_id' => $signatory->college_id,
                            'user_id' => $signatory->user_id,
                            'name' => $signatory->name,
                            'designee_id' => $signatory->designee_id,
                            'order' => $signatory->order,
                            'purpose_id' => $signatory->purpose_id,
                            'semester_id' => $newsem_id,
                            'updated_at' => $signatory->updated_at ?$signatory->updated_at:'',
                            'created_at' => $signatory->created_at ? $signatory->created_at : '',
                            ]
                        
                        );
                    }
    }
}
