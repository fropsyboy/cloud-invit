<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    //
    protected $fillable = [
        'user_id', 'custom_id'
    ];
}
