<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sharing extends Model
{
    //
    protected $fillable = [
        'mailing_id', 'ref', 'name', 'email'
    ];
}
