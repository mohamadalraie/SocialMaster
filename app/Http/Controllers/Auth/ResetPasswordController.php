<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    use ApiResponseTrait;
    public function forgot_Password(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($codeData->email)->send(new SendCodeResetPassword($codeData->code));


       return $this->ApiResponse(null,'The code has been sent to your private email please check',200);
    }


    public function check_code(Request $request)
    {
      $data =   $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $data['code']);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addDay()) {
            $passwordReset->delete();
            return response(['message' => trans('the code_is_expire through ine day you must try send another code again   ')], 422);
        }

        // delete current code
        $passwordReset->delete();
        return response([
            'message' => trans('the code is correct and valid')
        ], 200);

    }


    public function reset_password(Request $request)
    {
        $data =   $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|confirmed|min:8',
        ]);
        // find user's email
        $user = User::firstWhere('email', $request->email);
        // update user password
        $user->update($request->only('password'));

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
