<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Move;
use App\Services\DTO\Board;
use App\Services\DTO\Ship;

class GameService
{

    private $sessionId;


    /**
     * @param string $sessionId
     * @return $this
     */
    public function session($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Creates and return new game
     * @return Game
     */
    public function createGame()
    {
        $game = (new Game())
            ->setOwnerSessionId($this->sessionId);

        $game->save();

        return $game;
    }


    public function joinGame(Game $game)
    {
        if($game->getOpponentSessionId()===$this->sessionId)
            return true;

        if($game->getOpponentSessionId()===null) {
            $game->setOpponentSessionId($this->sessionId);
            return $game->update();
        }

        return false;
    }


    /**
     * @param Game $game
     * @return Board
     */
    public function getBoard(Game $game)
    {
        $moves = $game->getMoves($this->sessionId)->get();

        return (new Board())->map($moves);

    }

    /**
     * @param Game $game
     * @param Ship $ship
     * @return Board
     */
    public function addShip(Game $game, Ship $ship)
    {
        $board = $this->getBoard($game)->addShip($ship);

        $move = new Move;

        $move->addState($board);


        $move->save();


        return $board;
    }



}
