<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function chats(){
        return $this->hasMany(Chat::class, 'sender', 'receiver');
    }
    public function messages(){
        return $this->hasMany(ChatMessage::class, 'sender');
    }

    public function getProfile()
    {
        if ($this->avatar == null | $this->avatar == "") {
            $profile = url('default.jpg');
        } else {
            $profile = url('users/' . $this->id . '/' . $this->avatar);
        }
        return $profile;
    }

    /*
     * the function that check the status of the user .
     * if online => the return true.
     * if offline => then return false.
     */
    public function isOnline()
    {
        return Cache::has('online_user-'.$this->id);
    }
}
