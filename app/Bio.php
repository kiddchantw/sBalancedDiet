<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bio extends Model
{
    use SoftDeletes;

    //
    protected $table = 'bios';
    public $timestamps =   true;

//    protected $primaryKey = ['id'];

    protected $fillable = ['user_id','type','value'];

    protected $hidden = ['updated_at','deleted_at','' ];


    public function getTypeAttribute($type)
    {
        if ($type == 1) return '體重';
        if ($type == 2) return '血壓';
        return '未知';
    }

    public function getValueAttribute($value)
    {
        $number = ($value == (int) $value) ? (int) $value : (float) $value;
        return $number;
    }

}
