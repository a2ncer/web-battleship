<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Services\DTO\Ship;
use App\Services\GameService;
use App\Http\Controllers\Controller;

class BoardController extends Controller
{

    public function addShip(Game $game, Ship $ship, GameService $service)
    {

        $board = $service->addShip($game, $ship);

        dd($board->getBoard());

        return response()->json(["addShip"=>$board->getBoard()]);


    }
}
