<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $connection = 'mysql';


    protected $table = 'users';

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
       // 'email_verified_at','updated_at','created_at','remember_token','token_expire_time','deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'email_verified_at','updated_at','deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'  => 'date:Y-m-d H:i:s',
        'updated_at'  => 'date:Y-m-d H:i:s',
        'deleted_at'  => 'date:Y-m-d H:i:s',
    ];

    public function setPasswordAttribute($password)
    {
        if ($password !== null & $password !== "") {
            $this->attributes['password'] = Hash::make($password);
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Taipei')
            ->toDateTimeString()
            ;
    }


    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Taipei')
            ->toDateTimeString()
            ;
    }


    //todo:check if null?
    public function currentBio()
    {
        return $this->hasMany('App\bioProfile','user_id')
            ->orderBy('updated_at','desc')
            ->first()
            ;
    }

    public function currentStandard()
    {
        return $this->hasMany(userDiet::class,'user_id')
            ->select('fruits', 'vegetables', 'grains', 'nuts', 'proteins', 'dairy')
            ->where('kind', '=', 1)
            ->first();
    }

}
