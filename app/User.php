<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    static function is_simple(){
        if(!Auth::check())return false;
        return Auth::user()->privilege == 0?true:false;
    }

    static function is_student(){
        if(!Auth::check())return false;
        return Auth::user()->privilege == 1?true:false;

    }

    static function is_teacher(){
        if(!Auth::check())return false;
        return Auth::user()->privilege == 2?true:false;
    }

   static function is_admin(){
        if(!Auth::check())return false;
        return Auth::user()->privilege == 9999?true:false;
    }



}
