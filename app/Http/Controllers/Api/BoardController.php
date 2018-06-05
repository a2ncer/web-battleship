<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Services\DTO\Point;
use App\Services\DTO\Ship;
use App\Services\GameService;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{
    /**
     * Add a ship on board.
     *
     * @param Game        $game
     * @param Ship        $ship
     * @param GameService $service
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addShip(Game $game, Ship $ship, GameService $service)
    {
        $board = $service->addShip($game, $ship);

        return response()->json(['ship'=>$ship->getCoordinates(),'board' => $board->getBoard()]);
    }

    public function attack(Game $game,Point $point, GameService $service)
    {
        $attack = $service->attack($game,$point);

        return response()->json(["status"=>$attack]);

    }
}
