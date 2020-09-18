<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;



class bioProfile extends Model
{
    //
    use SoftDeletes;

    protected $table = 'bio_profiles';

    protected $fillable = [
        'user_id', 'weight',
        'systolic',   //收縮壓
        'diastolic'   //舒張壓(spirnt98)
    ];
    protected $hidden = ['updated_at','deleted_at','systolic','diastolic'];

    protected $casts = [
        'created_at'  => 'date:Y-m-d H:i:s',
        'updated_at'  => 'date:Y-m-d H:i:s',
        'deleted_at'  => 'date:Y-m-d H:i:s',
    ];

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


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
