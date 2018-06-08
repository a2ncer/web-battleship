<?php

namespace App\Models;

use App\Services\DTO\Point;
use App\Services\DTO\Ship;
use App\Services\Enums\MoveType;
use App\Services\Enums\SenderType;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;

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

    /**
     * @return mixed
     */
    public function getOwnerSessionId()
    {
        return $this->owner_session_id;
    }

    /**
     * @param $opponent_session_id
     * @return $this
     */
    public function setOpponentSessionId($opponent_session_id)
    {
        $this->opponent_session_id = $opponent_session_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpponentSessionId()
    {
        return $this->opponent_session_id;
    }

    /**
     * @param $sessionId
     * @return null|string
     */
    public function getSenderType($sessionId)
    {
        if($sessionId===null)
            return null;

        if ($this->getOwnerSessionId() === $sessionId) {
            return SenderType::OWNER;
        }

        if ($this->getOpponentSessionId() === $sessionId) {
            return SenderType::OPPONENT;
        }

        return null;
    }

    /**
     * @param $sessionId
     * @return null|string
     */
    public function getReversedSenderType($sessionId)
    {
        if ($this->getOwnerSessionId() === $sessionId) {
            return SenderType::OPPONENT;
        }

        if ($this->getOpponentSessionId() === $sessionId) {
            return SenderType::OWNER;
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


    /**
     * @param $event
     * @param $sessionId
     * @param Point $point
     */
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


    /**
     * @param $sessionId
     * @param Ship $ship
     */
    public function saveShip($sessionId, Ship $ship)
    {
        /** @var Point $point */
        foreach ($ship->getCoordinates() as $point) {
            $this->saveMove(MoveType::ADD_SHIP, $sessionId, $point);

        }
    }


    /**
     * @param $sessionId
     * @param Point $point
     */
    public function saveAttack($sessionId, Point $point)
    {
        /** @var Move $move */
        $move = $this->getMoves()->where('event',MoveType::ATTACK)->get()->last();
        $opponent = $this->getReversedSenderType($sessionId);

        if($move) {

            if($move->getSender() === $opponent) {

                $this->saveMove(MoveType::ATTACK, $sessionId, $point);
            }
            else {
                throw new Exception("Wait for your opponent turn");
            }

        }
        else
        {
            if($this->getOwnerSessionId() === $sessionId) {

                $this->saveMove(MoveType::ATTACK, $sessionId, $point);
            }
            else {
                throw new Exception("Can't attack, owner goes first");
            }
        }
    }


}
