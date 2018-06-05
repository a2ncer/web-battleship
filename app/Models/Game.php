<?php

namespace App\Models;

use App\Services\DTO\Point;
use App\Services\DTO\Ship;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $owner_session_id
     *
     * @return $this
     */
    public function setOwnerSessionId($owner_session_id)
    {
        $this->owner_session_id = $owner_session_id;

        return $this;
    }

    public function getOwnerSessionId()
    {
        return $this->owner_session_id;
    }

    public function setOpponentSessionId($opponent_session_id)
    {
        $this->opponent_session_id = $opponent_session_id;

        return $this;
    }

    public function getOpponentSessionId()
    {
        return $this->opponent_session_id;
    }

    public function getSenderType($sessionId)
    {
        if ($this->getOwnerSessionId() === $sessionId) {
            return 'OWNER';
        }

        if ($this->getOpponentSessionId() === $sessionId) {
            return 'OPPONENT';
        }

        return null;
    }

    public function getReversedSenderType($sessionId)
    {
        if ($this->getOwnerSessionId() === $sessionId) {
            return 'OPPONENT';
        }

        if ($this->getOpponentSessionId() === $sessionId) {
            return 'OWNER';
        }

        return null;
    }



    /**
     * @param $sessionId
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getMoves($sessionId = null)
    {
        $senderType = $this->getSenderType($sessionId);

        $moves = $this->hasMany('App\Models\Move');

        if ($senderType !== null) {
            $moves->where('sender', $senderType);
        }

        return $moves;
    }


    public function saveMove($event, $sessionId, Point $point)
    {
       (new Move())
            ->setEvent($event)
            ->setGameId($this->getId())
            ->setSender($this->getSenderType($sessionId))
            ->setX($point->getX())
            ->setY($point->getY())
            ->save();
    }


    public function saveShip($sessionId, Ship $ship)
    {
        /** @var Point $point */
        foreach ($ship->getCoordinates() as $point) {
            $this->saveMove("SHIP", $sessionId, $point);

        }
    }


}
