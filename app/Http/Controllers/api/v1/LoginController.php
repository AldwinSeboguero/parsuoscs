<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Student;
use App\Role;
class LoginController extends Controller
{
    public function login(Request $request){
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        if(!Auth::attempt($login)){
            return response(['message'=> 'Incorrect email or password!'],403);
        }

        $accessToken =Auth::user()->createToken('authToken')->accessToken;    
        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }
    public function register(Request $request){
       
        if ($request->password == $request->rpassword) {
               $student= Student::find($request->student);
                $user = User::create([
                    'name' => $student->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'username' => $request->email,
                ]);
                // $user['name'] = $data->name;
                // $user['email'] =$data->email;
                // $user['password'] = Hash::make($data->password);
                // $user['username'] = $data->username;
                // User::insert($user);
                $role =Role::where('name','student')->first();
                $user->roles()->attach($role);
                $student->is_activated = true;
                $student->user_id = $user->id;
                $student->update();
                return response(['message' => 'Please try your newly registered account!'],200);
        }
        else{
            return response(['message' => 'Mismatch Password!']);
        }
    }
}
