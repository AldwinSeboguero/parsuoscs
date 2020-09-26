<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use App\Http\Controllers\Controller;
class UserController extends Controller
{
    public function login(Request $request){
       
        $email = $request->email;
        $password = bcrypt($request->password);
        $user = User::where('email',$email)->where('password',$password)->first();
        
        if ($user) {
            $token =Hash::make($request->password);
            $user->remember_token = $token;
            $user->save();
            return response()->json(['token' => $token],200);
        }
        else{
        return response()->json(['status' => 'Email or Password is Wrong!'.$password],403);
        }
        
    }
}
