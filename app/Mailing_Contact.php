<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailing_Contact extends Model
{
    //
    protected $table = 'mailing_contacts';

    protected $fillable = [
        'contact_id', 'plus', 'status', 'user_id', 'mailing_id'
    ];

    public function contact()
    {
        return $this->belongsTo('App\Contact','contact_id');
    }

    public function mailing()
    {
        return $this->belongsTo('App\Mailing','mailing_id');
    }
}
