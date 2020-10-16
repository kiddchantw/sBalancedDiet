<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class userDiet extends Model
{
    //
    use SoftDeletes;

    protected $table = 'user_diets';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id',
        'created_at','updated_at','deleted_at',
        'kind','diet_type',
        'fruits','vegetables','grains','nuts','proteins','dairy','water'
    ];

    protected $hidden = ['deleted_at','created_at'];




    // 設定postman response的 時間格式
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
    ///




    protected $casts = [
        'created_at'  => 'date:Y-m-d H:i:s',
        'updated_at'  => 'date:Y-m-d H:i:s',
        'deleted_at'  => 'date:Y-m-d H:i:s',
    ];

    public function getKindAttribute($kind)
    {
        switch ($kind){
            case 0 :
                return  'daily';
            case 2 :
                return 'doctor';
            case 1:
                return 'personal';
            default:
                return 'undefinded';
        }
    }

    public function getDietTypeAttribute($diet_type)
    {
        switch ($diet_type){
            case 0 :
                return 'standard';
            case 1 :
                return 'breakfast';
            case 2:
                return 'lunch';
            case 3:
                return 'dessert';
            case 4:
                return 'dinner';
            case 5:
                return 'supper';
            default:
                return 'undefined';
        }
    }


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
