<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function events()
    {
        return $this->hasMany('App\Ticket');
    }
}
