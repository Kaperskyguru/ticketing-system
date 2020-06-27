<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $fillable = [
        'title',
        'description',
        'ticket_price',
        'event_date',
    ];

    public function events()
    {
        return $this->hasMany('App\Ticket');
    }
}
