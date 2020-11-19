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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page =$request->per_page ? $request->per_page : 10; 
        return response()->json([
        'users' => new UserCollection(UserRole::orderBy('user_id')->with('user')->with('role')->paginate($per_page)),
        'roles' => Role::pluck('description')->all()
        ],200);
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
            $role = Role::where('description',$request->role)->first();
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), 
            ]);  
            $user->save();
            $user->roles()->attach($role);
            $userRole = UserRole::where('user_id',$user->id)->where('role_id',$role->id)->first();
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
        return response()->json([
        'users' => new UserCollection(UserRole::orderBy('user_id')->with('user')->with('role')
        ->whereHas('user', function($q) use ($id)
        {
            $q->where('name','ILIKE','%'.$id.'%');

        })->paginate(10)),
        'roles' => Role::pluck('description')->all()
        ],200);
       
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
        $role = Role::where('description',$request->role)->first();
        $user = User::find($id);
        $user->name= $request->name;
        $user->email=$request->email;  
        if ($request->password) { 
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $user->roles()->detach();
        $user->roles()->attach($role);
        $userRole = UserRole::where('user_id',$user->id)->where('role_id',$role->id)->first();
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
        return $request->user();
    }
    public function verifyEmail(Request $request){
        $request->validate([
            'email' => 'required|unique:users'
        ]);

        return response()->json(['message' => 'Valid Email'], 200);
    }
    public function changeRole(Request $request){
        $role = Role::where('description',$request->role)->first();
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
}
