<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public $fillable = [
        'user_id', 'event_id', 'amount', 'code'
    ];

    protected $dates = ['date_used'];
}
