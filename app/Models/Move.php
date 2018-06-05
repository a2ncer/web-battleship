<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
    //
    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getEvent()
    {
        return $this->event;
    }


}
