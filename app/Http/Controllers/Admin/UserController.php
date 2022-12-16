<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Role; 
use App\UserRole;
use Illuminate\Support\Facades\Hash;    
use Illuminate\Support\Str;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use App\Staff;
use App\Notifications\PasswordResetRequest;
use Notification;
use App\Student;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->hasRole("admin")){
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'users' => new UserCollection(User::orderByDesc('updated_at')->paginate($per_page)),
        'isAdmin' => Auth::User(),
        'roles' => Role::all(),

        
        ],200);
        }
        else{
            $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'users' => new UserCollection(User::orderByDesc('updated_at')->whereHas('role', function($q){
            $q->where('role_id',2);
        })->paginate($per_page)),
        'isAdmin' => Auth::User(),
        'roles' => Role::where('id',2)->get(),

        
        ],200);
        }
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
    public function store(Request $request)
    {
            $role = Role::where('id',$request->role_id)->first();

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
                'username' => $request->email,
            ]);  
            $user->save();
            $user->roles()->attach($role);
            $userRole = UserRole::where('user_id',$user->id)->where('role_id',$request->role_id)->first();
            return response()->json(['user'=> new UserResource($userRole)],200);
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        if(Auth::user()->hasRole("admin")){
            return response()->json([
                'users' => new UserCollection(User::orderByDesc('updated_at')
                ->where('name','ILIKE','%'.$id.'%')
                ->orWhere('email','ILIKE','%'.$id.'%')
                ->paginate(10)),
                'roles' => Role::get(),

                ],200);
            }
            else{
                return response()->json([
                    'users' => new UserCollection(User::orderByDesc('updated_at')
                    ->whereHas('roles', function($q){
                        $q->where('role_id',2);
                    })
                    ->when($id, function($q) use($id){
                        $q->where('name','ILIKE','%'.$id.'%');
                            
                    })
                    ->when($id, function($q) use($id){
                        $q->where('email','ILIKE','%'.$id.'%');
                            
                    })
                    
                    
                    ->paginate(10)),
                    'roles' => Role::where('id',2)->get(),
    
                    ],200);
            }
       
       
    }

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
        // dd($request->role_id);
        $role = Role::where('id',$request->role_id)->first();
        $user = User::find($id);
        $user->name= $request->name;
        $user->email=$request->email;  
        if ($request->password) { 
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $user->roles()->detach();
        $user->roles()->attach($role);
        $userRole = User::where('id',$user->id)->first();
        return response()->json(['user'=> new UserResource($userRole)],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->delete();
        return response()->json(['user' => $user],200);
    }
    public function deleteAll(Request $request)
    {
       User::whereIn('id', $request->users)->delete();
        return response()->json(['message' , 'Records Deleted Successfully'],200);
    }
    public function login(Request $request)
    {

        $email = $request->email;
        $password = $request->password;
        $user = User::where('email',$email)->first();

        // if (Hash::check($password, $user->password)) {
        //     $token =Hash::make($password);
        //     $user->remember_token = $token;
        //     $user->save();

        //     return response()->json(['token' => $token,'userRole' => $user->role()],200);
        // }
        // else{
        // return response()->json(['status' => 'Email or Password is Wrong!'.$password],403);
        // }

        if ($request->ajax()) {
            $credentials = $request->only('email', 'password');
            if (
            Auth::attempt(['email' => $email, 'password' => $password]) ) {
                $token = Str::random(80);
                Auth::user()->remember_token = $token;
                Auth::user()->save();
                $admin = Auth::user()->isAdmin();
                return response()->json(['token' => $token, 'isAdmin' => $admin, 'user' => $user], 200);
            }
        }
        return response()->json(['status' => 'Email or Password is Wrong!'],403);
    }
    public function verify(Request $request){
        if (Auth::check()) {
            $student = null;
            if(Auth::user()->hasRole("student")){
           $student = Student::where('user_id',Auth::user()->id)->first();
            }
            return response()->json([
            'status' => true,
            'user_role'=> UserRole::with('role')->where('user_id',Auth::user()->id)->first(),
            'staff_campus' => Auth::user()->hasRole("osas") ?
            UserRole::with('role')->where('user_id',Auth::user()->id)->first()->role->name == "student" ? 0 : Staff::where('user_id',Auth::user()->id)->first()->campus->name
            : null,
            'student' => $student ? $student->program->college->name : $student,

            
        ],200);
              # code...
        }
        else{
            return response()->json(['status' => false],403);
        }
        
    }
    public function verifyEmail(Request $request){
        $request->validate([
            'email' => 'required|unique:users'
        ]);

        return response()->json(['message' => 'Valid Email'], 200);
    }
    public function changeRole(Request $request){
        $role = Role::where('id',$request->role_id)->first();
        $user = User::find($request->user);  
        $user->roles()->detach();
        $user->roles()->attach($role);
        $userRole = UserRole::where('user_id',$user->id)->where('role_id',$role->id)->first();
        return response()->json(['user'=> new UserResource($userRole)],200);

    }

    //get the authenticate user info
    public function userInfo(Request $request){
        $user = $request->user();
        return response()->json(['user'=> $user],200);
    }
    public function changePassword(Request $request){
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request->password);
        $user->save(); 
        return response()->json(['user'=> $user],200);
    }
    public function updateEmail(Request $request){
        Notification::route('mail', 'aldwin.seboguero@parsu.edu.ph') 
            ->notify(new PasswordResetRequest("sasasas"));
        return response()->json(['user'=> $user],200);
    }
}
