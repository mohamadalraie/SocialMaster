<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Models\User_profile;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiResponseTrait;
    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|max:125',
            'last_name' => 'required|max:125',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',


        ]);
        $user = User::create($data);
       $user->user_profile()->create([]);
       $token = $user->createToken('User Api Token')->accessToken;
    return  $this -> ApiResponse([ 'user' => $user, 'token' => $token],'Account created successfully',200);
    }

    public  function complete_register(Request $request){
        $data = $request->validate([
            'phone_num' => 'required|numeric|unique:users',
            'gender'=>'required',
            'birthdate'=>'required|date',
            'profile_photo'=>'image']);

         $user_id = Auth::id();
         $user = User::where('id',$user_id)->first();
         $user->update(['phone_num'=>$request['phone_num']
             ,'gender'=>$request['gender'],
             'birthdate'=>$request['birthdate'],
             ]);

         if($request->profile_photo){
             $imageName = time().'.'.$request->profile_photo->extension();
             $request->profile_photo->storeAs('images/profile_picture', $imageName);
             $name_path='storage/app/images/profile_picture/'.$imageName;
            $user->user_profile()->update(['profile_photo'=>$name_path]);
         }

         return $this->ApiResponse(null,'complete information its succesful',200);
    }


    public function login(Request $request)
    {
       $data = $request->validate(['emailORmobile' => 'required',
            'password' => 'required|min:8']);
       $email_mobile = $request->get('emailORmobile');
       $password = $request->get('password');
        $user = User::where('phone_num', $email_mobile)->orWhere('email', $email_mobile)->first();

       if(!$user  || !Hash::check($password, $user->password)){
           return $this -> ApiResponse(['data'=>null], 'some entered data are incorrect',400);
       }
        $token = $user->createToken('User Api Token')->accessToken;

        return  $this -> ApiResponse([ 'user' => $user, 'token' => $token],'login successfully',200);
    }
    public function logout()
    {

        $token = auth()->user()->token();
        $token->revoke();
        $token->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ],200);
    }



}
