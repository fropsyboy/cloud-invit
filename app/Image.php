<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //

    protected $fillable = [
        'name', 'custom_id', 'status', 'path', 'front', 'back'
    ];
}
