<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Staff_PD;
use App\User;
use App\UserV2;

use App\UserRole;
use App\Designee;
use App\Staff;
use App\Campus;
use App\Semester;
use App\Staff_DEAN; 
use App\StaffRegistrar;
use App\StaffStCouncil;
use App\Purpose;
use App\Program;
use App\Staff_Adviser;
use App\Http\Resources\Staff as StaffResource;
use App\Http\Resources\StaffCollection;
use App\Http\Resources\UserStaffCollection;

use App\SignatoryV2;


class StaffController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 

        // $staffs =  new StaffCollection(SignatoryV2::orderBy('designee_id')
        // ->orderByDesc('updated_at')
        // ->paginate($per_page));
        // return response()->json([
        //     'staffs' => $staffs,
        //     'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
        //     function ($q){
        //         $q->where('name','!=','student');
        //     }
        //     )->get(),
        //     'designations' => Designee::orderBY('id')->get(),
        //     'campuses' => Campus::orderBY('id')->get(),
        //     'semesters' => Semester::orderByDesc('created_at')->get(),
        //     ],200);
        $signatories = new UserStaffCollection(UserV2::with(['designees' => function($q) {
                $q->distinct('user_id');
            }])->withCount('designees')->has('designees')
            // ->whereHas('designees', function($q){
            //     $q->where('designee_id',1);
            // })
            ->paginate(104));
        return response()->json([
            'signatories' => $signatories
        ]);
        
    }

    public function getStaff(Request $request){
        // $semester =  Semester::orderByDesc('id')->when($request->semester, function ($q) use ($request){
        //     $q->where('id',$request->semester);
        // })->first();
        $semester_name = Semester::orderByDesc('id')->when($request->semester, function ($q) use ($request){
            $q->where('id',$request->semester);
        })->first()->semester;
        $designation = $request->designation;
        $campus = $request->campus;
        $program = $request->program;
        $signatory = $request->signatory;
        // $purpose = Purpose::orderBy('id')->when($request->purpose, function ($q) use ($request){
        //     $q->where('id',$request->purpose);
        // })->get()->first();
        $purpose = $request->purpose;
        $semester = $request->semester;

        $staffs =  new StaffCollection(SignatoryV2::orderByDesc('semester_id')->orderBy('purpose_id')->orderBy('order')
        ->orderBy('designee_id')
        ->when($signatory, function($q) use($signatory){
            $q->where('user_id', $signatory);
        })
        ->when($campus, function($q) use($campus){
            $q->where('campus_id', $campus);
        })
        ->when($semester, function($q) use($semester){
            $q->where('semester_id', $semester);
        })
        ->when($designation, function($q) use($designation){
            $q->where('designee_id', $designation);
        })
        ->when($purpose, function($q) use($purpose){
            $q->where('purpose_id', $purpose);
        })
        ->when($program, function($q) use($program){
            $q->where('program_id', $program);
        })
        // ->where('campus_id', 1)
        // ->where('semester_id', Semester::orderByDesc('id')->first()->id)
        // ->where('purpose_id', 2)
        // ->groupBy('user_id')
        ->paginate($request->per_page));
        // $staffs = new UserStaffCollection(UserV2::has('designee')
        // // with(['designees' => function($q) {
        // //     $q->distinct('user_id');
        // // }])
        // // ->
        // // $q->whereHas('designees', function($q) use($signatory){
        // //     $q->where('user_id', $signatory);
        // // });
        // // ->whereHas('designees', function($q){
        // //     $q->orderByDesc('semester_id');
        // // })
        // ->when($signatory, function($q) use($signatory){
        //     $q->whereHas('designees', function($q) use($signatory){
        //         $q->where('user_id', $signatory);
        //     });
        // })
        // ->when($campus, function($q) use($campus){
        //     $q->whereHas('designees', function($q) use($campus){
        //         $q->where('campus_id', $campus);
        //     });
        // })
        // ->when($semester, function($q) use($semester){
        //     $q->whereHas('designees', function($q) use($semester){
        //         $q->where('semester_id', $semester->id);
        //     });
        // })
        // ->when($designation, function($q) use($designation){
        //     $q->whereHas('designees', function($q) use($designation){
        //         $q->where('designee_id', $designation);
        //     });
        // })
        // // ->when($purpose, function($q) use($purpose){
        // //     $q->whereHas('designees', function($q) use($purpose){
        // //         $q->where('purpose_id', $purpose->id);
        // //     });
        // // })
        // ->when($program, function($q) use($program){
        //     $q->whereHas('designees', function($q) use($program){
        //         $q->where('program_id', $program);
        //     });
        // })
        // ->paginate($request->per_page));
        $users = User::whereHas('roles', function($q){
            $q->where('role_id',2);
        })->withCount('studentAccount')->doesnthave('studentAccount')->paginate(5);
        $programs = Program::doesnthave('signatories')->paginate(5);

        return response()->json([
            // 'users' => new UserStaffCollection(UserV2::with(['designees' => function($q) {
            //     $q->distinct('designee_id');
            // }])->withCount('designees')->has('designees')->paginate(104)),
            //  'users2' => UserV2::with('designees')->withCount('designees')->paginate(),
            'programs' => $programs,
            'user_with_no_student_act' => $users,
            'table_data' => $staffs,
            'semester' =>  $semester,
            'semester_name' => $semester_name,
            'headers' => [
                [
                  'text'=> '#',
                  'align'=> 'start',
                  'sortable'=> false,
                  'value'=> 'id',
                //   'class'=> 'blue darken-4 white--text',
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Name', 
                  'sortable'=> false,
                  'value'=> 'name',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Designee', 
                  'sortable'=> false,
                  'value'=> 'designee_name',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Semester', 
                  'sortable'=> false,
                  'value'=> 'semester',
                //   'class'=> 'blue darken-4  white--text',
                  'class' => 'blue--text text--darken-3 font-weight-black '
                 ],
                [ 'text'=> 'Purpose', 
                  'sortable'=> false,
                  'value'=> 'purpose',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                [ 'text'=> 'Order', 
                  'sortable'=> false,
                  'value'=> 'order',
                //   'class'=> 'blue darken-4  white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                ],
                  [ 'text'=> '', 
                  'sortable'=> false,
                  'value'=> 'actions',
                //   'class'=> 'blue darken-4 white--text', 
                  'class' => 'blue--text text--darken-3 font-weight-black '
                  ]
              ],
        ]);
    }


    public function changeStaff(Request $request){
        // $staff = Staff::find($request->staff); 
        // $new_staff = $request->new_staff;
        // // $changeStaff =Staff::where('user_id',$staff->user_id)->where('semester_id',$staff->semester_id)
        // // ->first();
        // // // dd($changeStaff->user_id);
        // // $changeStaff->user_id = $new_pd; 
        // // $changeStaff->save(); 
        // $staff->user_id=$new_staff; 
        // $staff->save();
      
        // $per_page =$request->per_page ? $request->per_page : 10; 
        // $staff =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')
        // ->paginate($per_page));
        // return response()->json([
        //     'staffs' => $staff,
        //     ],200);

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10;
        return response()->json([
            'staffs' => new StaffCollection(
                SignatoryV2::with('user')->with('semester')->with('campus')
                ->whereHas('user', function($q) use ($id){
                    $q->where('name', 'ILIKE', '%' . $id . '%');
                }) 
            ->orwhereHas('campus', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->orWhereHas('semester', function($q) use ($id){
                $q->where('semester', 'ILIKE', '%' . $id . '%');
            }) 
            ->paginate($per_page)),
            'user_staff' => UserRole::orderBy('user_id')->with('user')->with('role')->whereHas('role',
            function ($q){
                $q->where('name','!=','student');
            }
            )
            ->whereHas('user', function($q) use ($id){
                $q->where('name', 'ILIKE', '%' . $id . '%');
            }) 
            ->get(),
            'designations' => Designee::orderBY('id')->get(),
            'campuses' => Campus::orderBY('id')->get(),
            'semesters' => Semester::orderBY('id')->get(),
            ],200);
        //
    }

    public function store(Request $request)
    { 
        $designation = $request->designation;
        $campus = $request->campus;
        $program = $request->program;
        $signatory = $request->signatory;
        $purpose = $request->purpose;
        $semester = $request->semester;
        $order = $request->order;

        if($designation && $campus && $program && $signatory && $purpose && $semester && $order){
            $hasSignatory = 0;
            $hasSignatory = SignatoryV2::when($campus, function($q) use($campus){
                $q->where('campus_id', $campus);
            })
            ->when($semester, function($q) use($semester){
                $q->where('semester_id', $semester);
            })
            ->when($designation, function($q) use($designation){
                $q->where('designee_id', $designation);
            })
            ->when($purpose, function($q) use($purpose){
                $q->where('purpose_id', $purpose);
            })
            ->when($program, function($q) use($program){
                $q->where('program_id', $program);
            })->paginate(5)->total();
            $program_id = Program::find($program);
            $user = User::find($signatory);
            $designee = Designee::find($designation);
            if(!$hasSignatory){
                
                SignatoryV2::create(
                    [
                    'program_id' => $program_id->id,
                    'campus_id' => $program_id->campus_id,
                    'college_id' => $program_id->college_id,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'designee_id' => $designation,
                    'order' => $order,
                    'purpose_id' => $purpose,
                    'semester_id' => $semester,
                    ]
                
                );
            }
            else{
                return response()->json([
                    'message' => $designee->name.' for '.$program_id->name.' already exist!',
                ],500);
            }

            return $hasSignatory;

        }
        else{
            return response()->json([
                'message' => 'Missing required data! Please complete filling out the forms.'
            ],500);
        }
        
            // $staff = Staff::firstOrCreate([
            // 'user_id' => $request->user_id,
            // 'campus_id'=> $request->campus_id, 
            // 'semester_id'=> $request->semester_id,
            // 'designee_id'=> $request->designee_id,
            // ]);  
            // $staff->save(); 
            // $staffs =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')->with('designee')
            // ->paginate(5));
            // return response()->json(['staffs'=>$staffs,200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   

    /** 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $designation = $request->designation;
        $campus = $request->campus;
        $program = $request->program;
        $signatory = $request->signatory;
        $purpose = $request->purpose;
        $semester = $request->semester;
        $order = $request->order;
        $signatory_id = $request->id;


        if($designation && $campus && $program && $signatory && $purpose && $semester && $order){
            $hasSignatory = 0;
            $hasSignatory = SignatoryV2::when($campus, function($q) use($campus){
                $q->where('campus_id', $campus);
            })
            ->when($semester, function($q) use($semester){
                $q->where('semester_id', $semester);
            })
            ->when($designation, function($q) use($designation){
                $q->where('designee_id', $designation);
            })
            ->when($purpose, function($q) use($purpose){
                $q->where('purpose_id', $purpose);
            })
            ->when($program, function($q) use($program){
                $q->where('program_id', $program);
            })->paginate(5)->total();
            $program_id = Program::find($program);
            $user = User::find($signatory);
            $designee = Designee::find($designation);
            if($hasSignatory){
                $signatoryV2 = SignatoryV2::find($signatory_id);
                $signatoryV2->program_id = $program_id->id;
                $signatoryV2->campus_id = $program_id->campus_id;
                $signatoryV2->college_id = $program_id->college_id;
                $signatoryV2->user_id = $user->id;
                $signatoryV2->name = $user->name;
                $signatoryV2->designee_id = $designation;
                $signatoryV2->order = $order;
                $signatoryV2->purpose_id = $purpose;
                $signatoryV2->semester_id = $semester; 
                $signatoryV2->save();
                
            }
            else{
                return response()->json([
                    'message' => $designee->name.' for '.$program_id->name.' doesn\'t exist!',
                ],500);
            }

            return $hasSignatory;

        }
        else{
            return response()->json([
                'message' => 'Missing required data! Please complete filling out the forms.'
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staff = Staff::find($id)->delete();
        return response()->json(['staff' => $staff],200);
    } 
    public function getPrevDeanEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [2])
        ->where('college_id',$request->college)
        ->whereHas('program', function($q)use($request){
            $q->where('college_id',$request->college);
        })
        ->where('purpose_id',1)
        ->where('semester_id',$prevsem_id)
        ->get();
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevDeanEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();
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
        return response()->json([
            'result' => 'Copied Dean '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }
    public function getPrevCashierEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [3])
        ->where('campus_id',$request->campus)
        ->whereHas('program', function($q)use($request){
            $q->where('campus_id',$request->campus);
        })
        ->where('purpose_id',1)
        ->where('semester_id',$prevsem_id)
        ->get();
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevCashierEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();
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
        return response()->json([
            'result' => 'Copied Cashier '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }

    public function getPrevLibraryEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [5])
        ->where('campus_id',$request->campus)
        ->whereHas('program', function($q)use($request){
            $q->where('campus_id',$request->campus);
        })
        ->where('purpose_id',1)
        ->where('semester_id',$prevsem_id)
        ->get();
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevLibraryEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();
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
        return response()->json([
            'result' => 'Copied Librarian '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }

    public function getPrevOSASEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [6])
        ->where('campus_id',$request->campus)
        ->whereHas('program', function($q)use($request){
            $q->where('campus_id',$request->campus);
        })
        ->where('purpose_id',1)
        ->where('semester_id',$prevsem_id)
        ->get();
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevOSASEnrollment(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();

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
        return response()->json([
            'result' => 'Copied OSAS '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }

    public function getPrevGraduation(Request $request){
        $prevsem_id = $request->prev;

        $signatories = SignatoryV2::orderBy('designee_id')
                     ->where('campus_id',$request->campus)
                    ->whereHas('program', function($q)use($request){
                        $q->where('campus_id',$request->campus);
                    })
                    ->where('purpose_id',2)
                    ->where('semester_id',$prevsem_id)
                    
                    ->paginate(100);
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevGraduation(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();
        
                    
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

        return response()->json([
            'result' => 'Copied Graduation Signatory '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }

    public function getPrevRequestCredentials(Request $request){
        $prevsem_id = $request->prev;

        $signatories = SignatoryV2::orderBy('designee_id')
                    ->where('campus_id',$request->campus)
                    ->whereHas('program', function($q)use($request){
                        $q->where('campus_id',$request->campus);
                    })
                    ->where('purpose_id',3)
                    ->where('semester_id',$prevsem_id)
                    ->paginate(100);
        return response()->json([
            'signatories' => $signatories,
        ]);
    }
    public function copyPrevRequestCredentials(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;

        $prevsem_name = Semester::where('id',$prevsem_id)->first()->semester;
        $newsem_name = Semester::where('id',$newsem_id)->first()->semester;

        $signatory = SignatoryV2::orderBy('designee_id')
                    ->where('id',$request->signatory)
                    ->get()->first();
        
                    
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
            'updated_at' => $signatory->updated_at ? $signatory->updated_at:'',
            'created_at' => $signatory->created_at ? $signatory->created_at : '',
            ]
        
        );

        return response()->json([
            'result' => 'Copied Request Credentials Signatory '.$signatory->user->name.' Program: '.$signatory->program->short_name.' From Semester: '.$prevsem_name.' -> '.$newsem_name,
        ]);
    }

    public function copyPrev(Request $request){
        $prevsem_id = $request->prev;
        $newsem_id = $request->next;
        $signatories = SignatoryV2::orderBy('designee_id')->whereIn('designee_id', [3,6,5,2])
                    ->where('purpose_id',1)
                    ->where('semester_id',$prevsem_id)
                    ->get();
                    foreach($signatories as $key => $signatory){

                        
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

    public function copyPreviousStaff(Request $request){
        $prevsem_id = Semester::orderByDesc('id')->skip(1)->take(1)->get()->first()->id;
        $staffs = Staff::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_deans = Staff_DEAN::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_pds = Staff_PD::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_registrars = StaffRegistrar::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_stcouncils = StaffStCouncil::orderBy('id')->where('semester_id',$prevsem_id)->get();
        $staff_advisers = Staff_Adviser::orderBy('id')->where('semester_id',$prevsem_id)->get();
        foreach ($staffs as $key => $staff) {
            $st = Staff::firstOrCreate([
                'user_id' => $staff->user_id,
                'designee_id' => $staff->designee_id,
                'campus_id' => $staff->campus_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        // dd($staff_deans);
        foreach ($staff_deans as $key => $staff) {
            $st = Staff_DEAN::firstOrCreate([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_pds as $key => $staff) {
            $st = Staff_PD::firstOrCreate([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_registrars as $key => $staff) {
            $st = StaffRegistrar::firstOrCreate([
                'user_id' => $staff->user_id,
                'program_id' => $staff->program_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_stcouncils as $key => $staff) {
            $st = StaffStCouncil::firstOrCreate([
                'user_id' => $staff->user_id, 
                'college_id' => $staff->college_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        foreach ($staff_advisers as $key => $staff) {
            $st = Staff_Adviser::firstOrCreate([
                'user_id' => $staff->user_id, 
                'section_id' => $staff->section_id,
                'semester_id' => $request->new_semester_id,
            ]); 
            
        }
        $staffs =  new StaffCollection(Staff::orderByDesc('updated_at')->with('user')->with('semester')->with('campus')->with('designee')
        ->paginate(5));
        return response()->json(['staffs'=>$staffs,200]);
        // return response()->json(['Prev Sem'=>Semester::orderByDesc('id')->skip(1)->take(1)->get(),
        // 'New Sem' => $request->new_semester_id,
        // 200]);
    }
}
