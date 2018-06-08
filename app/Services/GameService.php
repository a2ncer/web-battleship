<?php

namespace App\Services;

use App\Models\Game;
use App\Services\DTO\Board;
use App\Services\DTO\Point;
use App\Services\DTO\Ship;
use App\Services\Enums\MoveType;

class GameService
{
    /**
     * @var string
     */
    private $sessionId;

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function session($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Creates and return new game.
     *
     * @return Game
     */
    public function createGame()
    {
        $game = (new Game())
            ->setOwnerSessionId($this->sessionId);

        $game->save();

        return $game;
    }

    /**
     * @param Game $game
     *
     * @return bool
     */
    public function joinGame(Game $game)
    {
        if ($game->getOpponentSessionId() === $this->sessionId) {
            return true;
        }

        if ($game->getOpponentSessionId() === null && $game->getOpponentSessionId() !== $this->sessionId) {
            $game->setOpponentSessionId($this->sessionId);

            return $game->update();
        }

        return false;
    }

    /**
     * @param Game $game
     *
     * @return Board
     */
    public function getSessionBoard(Game $game)
    {
        $senderType = $game->getSenderType($this->sessionId);

        $reversedSenderType = $game->getReversedSenderType($this->sessionId);

        $moves = $game->getMoves()
            ->where(function ($query) use ($senderType, $reversedSenderType) {
                $query->where([['event', '=', MoveType::ADD_SHIP], ['sender', '=', $senderType]])
                    ->orWhere([['event', '=', MoveType::ATTACK], ['sender', '=', $reversedSenderType]]);
            })
            ->get();

        return (new Board())->map($moves);
    }

    /**
     * @param Game $game
     *
     * @return Board
     */
    public function getOpponentBoard(Game $game)
    {
        $senderType = $game->getSenderType($this->sessionId);

        $reversedSenderType = $game->getReversedSenderType($this->sessionId);

        $moves = $game->getMoves()
            ->where(function ($query) use ($senderType, $reversedSenderType) {
                $query->where([['event', '=', MoveType::ADD_SHIP], ['sender', '=', $reversedSenderType]])
                    ->orWhere([['event', '=', MoveType::ATTACK], ['sender', '=', $senderType]]);
            })
            ->get();

        return (new Board())->map($moves);
    }

    /**
     * @param Game $game
     * @param Ship $ship
     *
     * @return Board
     */
    public function addShip(Game $game, Ship $ship)
    {
        $board = $this->getSessionBoard($game)->addShip($ship);

        $game->saveShip($this->sessionId, $ship);

        return $board;
    }

    /**
     * @param Game $game
     * @param Point $point
     *
     * @return bool
     */
    public function attack(Game $game, Point $point)
    {
        $attack = $this->getOpponentBoard($game)->attack($point);

        $game->saveAttack($this->sessionId, $point);

        return $attack;
    }

    public function getUpdates(Game $game)
    {
        $board = $this->getSessionBoard($game);

        $opponentBoard = $this->getOpponentBoard($game)->withOutShips();

        return ["board"=>$board->getBoard(), "opponentBoard"=>$opponentBoard];
    }
}
