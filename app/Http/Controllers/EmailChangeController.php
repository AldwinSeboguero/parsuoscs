<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\EmailChangeRequest;
use App\Notifications\EmailChangeSuccess;
use App\User;
use App\EmailChange;
use Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class EmailChangeController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::find(Auth::user()->id);
        if (!$user)
            return response()->json([
                'message' => 'Email already used!.'
            ], 404);
        $emailChange = EmailChange::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Str::random(60),
                'user_id' => $user->id
             ]
        );
        if ($user && $emailChange)
        Notification::route('mail', $request->email) 
        ->notify( new EmailChangeRequest($emailChange->token));
         
        return response()->json([
            'message' => 'We have e-mailed your email confirmation link!'
        ]);
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find(Request $request)
    {
        $emailChange = EmailChange::where('token', $request->token)
            ->first();
        if (!$emailChange)
            return response()->json([
                'message' => 'This email reset token is invalid.'
            ], 404);
        if (Carbon::parse($emailChange->updated_at)->addMinutes(720)->isPast()) {
            $emailChange->delete();
            return response()->json([
                'message' => 'This email reset token is expired.'
            ], 404);
        }
        else{
            $user = User::find($emailChange->user_id);
            if (!$user)
                return response()->json([
                    'message' => 'We cant find a user with that e-mail address.'
                ], 404);
            $user->email = $emailChange->email;
            $user->save();
            $emailChange->delete(); 
        return response()->json($user);
        }
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = EmailChange::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new EmailChangeSuccess($passwordReset));
        return response()->json($user);
    }
}