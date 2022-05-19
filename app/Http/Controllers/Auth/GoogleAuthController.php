<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Student;
use App\Role;
use Laravel\Socialite\Facades\Socialite; 
use Exception;
class GoogleAuthController extends Controller
{
    public function handleGoogleCallback()
    {
        try {
            //create a user using socialite driver google
            $user = Socialite::driver('google')->stateless()->user(); 
            // if the user exits, use that user and login
            // dd($user);
            // $finduser = User::where('google_id', $user->id)->first();
            // dd($user);
            $finduser = User::where('email', $user->email)->first();
            
            if($finduser){
                //if the user exists, login and show dashboard
                Auth::login($finduser);
                $accessToken =Auth::user()->createToken('authToken')->accessToken;    
        // return response(['user' => Auth::user(), 'access_token' => $accessToken]);
         

        $accessToken =Auth::user()->createToken('authToken')->accessToken;    
        return redirect('/');
            }else{
                //user is not yet created, so create first
                // $newUser = User::create([
                //     'name' => $user->name,
                //     'email' => $user->email,
                //     'google_id'=> $user->id,
                //     'password' => encrypt('')
                // ]);
                //every user needs a team for dashboard/jetstream to work.
                //create a personal team for the user
                // $newTeam = Team::forceCreate([
                //     'user_id' => $newUser->id,
                //     'name' => explode(' ', $user->name, 2)[0]."'s Team",
                //     'personal_team' => true,
                // ]);
                // // save the team and add the team to the user.
                // $newTeam->save();
                // $newUser->current_team_id = $newTeam->id;
                // $newUser->save();
                //login as the new user
                // Auth::login($newUser);
                // go to the dashboard
                return response('Not Register');
            }
            // catch exceptions
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        $accessToken =Auth::user()->createToken('authToken')->accessToken;    
        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }
    public function redirectToGoogle()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl(); 
        return response()->json([
            "url" => $url,'access_token' => "asas"
        ]);
    }
}
