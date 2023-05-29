<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\GoogleAuthController;
use App\Models\User;
use App\Models\User_profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Auth\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('/complete_register', [AuthController::class,'complete_register']);
});

Route::get('test',function (){
    $user = User_profile::get();

    return response([$user,'succes',200]);
});

 //////////////// authenctication /////////////////
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
//////////////// with google /////////////////////


Route::get('google/login/url', [GoogleAuthController::class,'getAuthUrl']);
Route::post('google/auth/login', [GoogleAuthController::class,'PostLoginByGoogle']);


///////////  reset password  ////////////////////
 Route::post('forget-password', [ResetPasswordController::class,'forgot_Password']);
Route::post('code-check',[ResetPasswordController::class,'check_code']);
Route::post ('update-password', [ResetPasswordController::class,'reset_password']);


