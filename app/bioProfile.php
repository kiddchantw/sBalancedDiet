<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


}