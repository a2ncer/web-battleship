<?php

namespace App\Models;

use App\Services\DTO\Ship;
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

    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function setEvent($event)
    {
        $this->event = $event;
        return $this;
    }

    public function setGameId($id)
    {
        $this->game_id = $id;
        return $this;
    }


}
