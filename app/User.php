<?php

namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\Notifications\PasswordReset; // Or the location that you store your notifications (this is default).

class User extends Authenticatable
{
    use  HasApiTokens,Notifiable;
    //use  Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
    
    protected $fillable = [
        'name', 'email', 'password','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function full_name() {
        return $this->name." ".$this->last_name;
    }

   
}
