<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use App\User;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
        }
    public function sendPasswordResetLink(Request $request)
    {

        $is_registered = User::where('email',$request->email)->first();
        if($is_registered){
            $resetpass = Str::random(5);
            $is_registered->password = Hash::make($resetpass);
            $is_registered->notify(new MailResetPasswordNotification($resetpass));
            return response()->json(['message' => 'Password reset email sent.'],200);
        }
        else{
            return response()->json(['message' => 'Email is not registered!'],500);
        }
        // return $this->sendResetLinkEmail($request);
    }

    protected function sendResetLinkResponse(Request $request, $response)
{
    return response()->json([
        'message' => 'Password reset email sent.',
        'data' => $response
    ]);
}
/**
 * Get the response for a failed password reset link.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  string  $response
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
 */
protected function sendResetLinkFailedResponse(Request $request, $response)
{
    return response()->json(['message' => 'Email could not be sent to this email address.']);
}
public function callResetPassword(Request $request)
{
    return $this->reset($request);
}
protected function resetPassword($user, $password)
{
    $user->password = Hash::make($password);
    $user->save();
    event(new PasswordReset($user));
}

protected function sendResetResponse(Request $request, $response)
{
    return response()->json(['message' => 'Password reset successfully.']);
}
/**
 * Get the response for a failed password reset.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  string  $response
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
 */
protected function sendResetFailedResponse(Request $request, $response)
{
    return response()->json(['message' => 'Failed, Invalid Token.']);
}
}
