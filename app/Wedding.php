<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    //

    protected $fillable = [
        'mailing_id', 'image', 'status'
    ];
}
