<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GameService;

class GameController extends Controller
{
    /**
     * Create new game.
     *
     * @param GameService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function create(GameService $service)
    {
        $game = $service->createGame();

        return response()->json($game);
    }

    /**
     * Display the specified game.
     *
     * @param \App\Models\Game $game
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return response()->json($game);
    }

    /**
     * Join game.
     *
     * @param Game        $game
     * @param GameService $service
     *
     * @return \Illuminate\Http\Response
     */
    public function join(Game $game, GameService $service)
    {
        $status = $service->joinGame($game);

        return response()->json(['joined' => $status, 'game' => ["id"=>$game->getId()]]);
    }

    public function getUpdates(Game $game, GameService $service)
    {
        $updates = $service->getUpdates($game);

        return response()->json($updates);

    }
}
