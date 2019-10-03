<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envelope extends Model
{
    //

    protected $fillable = [
        'name', 'status', 'path', 'front', 'back'
    ];
}
