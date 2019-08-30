<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    //

    protected $fillable = [
        'path', 'status', 'name', 'status', 'description'
    ];
}
