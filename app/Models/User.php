<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_num',
        'gender',
        'birthdate',
        'provider_id',
        'provider_name',
        'google_access_token_json',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /////////// setter and getter of model /////////////
    public  function  setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }
    public function setGenderAttribute($value)
    {
        if($value== 'female')
        $this->attributes['gender'] = 0;
        elseif($value == 'male')
            $this->attributes['gender'] = 1;
    }
    public function getGenderAttribute($value)
    {
        if($value == 0)
        return 'female';
        elseif($value == 1)
            return 'male';
    }
    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = Carbon::createFromFormat('d/m/Y', $value)->toDateString();
    }
    /////////// setter and getter of model /////////////
    ///
    ///
    //////////  Relationship //////////////////

    public function user_profile(){
       return  $this->hasOne(User_profile::class);
    }

//////////  Relationship //////////////////
}
