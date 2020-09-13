<?php

namespace App;

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


}
