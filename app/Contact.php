<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    //
    protected $fillable = [
        'name', 'email', 'status', 'user_id'
    ];

    public function sent()
    {
        return $this->hasMany('App\Mailing_Contact','contact_id');
    }

}
