<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class userWater extends Model
{
    //
    use SoftDeletes;

    protected $table = 'user_waters';


    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'water','created_at','updated_at','deleted_at'];

    protected $hidden = ['updated_at','deleted_at'];

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
}
