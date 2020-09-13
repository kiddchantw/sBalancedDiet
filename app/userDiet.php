<?php

namespace App;

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
        'fruits','vegetables','grains','nuts','proteins','dairy'
    ];

    protected $hidden = ['updated_at','deleted_at'];
}
