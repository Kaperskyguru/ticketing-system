<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function ticket()
    {
        return $this->belongsTo('App\Event');
    }
}
