## Battleship API

You can see simple **[demo](http://playinpoker.ml)**. You can create a new game 
and share game's ID with other user that can join and play with you.

**Simple interface manual:**
- `Start new Game` - create new game, shows game id.
- `Add Ship` - click on your board for x,y start position, select `size` and `direction`.
- `Join` - set Game Id and press Join button.
- For attacking - just click on opponent's board.

API Responses you can see in log area.


## Structure

Api controllers are located in app/Http/Controllers/Api.
There are 2 main models - Game and Move.
GameService is located in app/Http/Controllers/Services.
This service is responsible for game logic. 

The main idea is to save events in database (Move model) and then project all moves
into boards.

**What is completed:**
- Create an empty board.
- Place a ship on the board.
- Make an attacking move, determining if it is a hit or a miss and updating the game state.
- Determine the current state of the game, finished (and who won), in play.

**What is not completed:**
- Create a random board with the ships already placed.
- Request validation
- Filters for ship creation
- Notification via WebSockets (Redis - as broadcast server and nodejs - WebSocket server)
- UnitTests

## API Description


### Create new game
**POST**   /api/game/create

### Get game information
**GET**    /api/game/{game_id} 

### Join game by id
**PATCH**  /api/game/{game_id}/join

### Get current state of game
**GET**   /api/game/{game_id}/updates

### Add a ship on board
**POST**   /api/game/{game_id}/board/addShip

**fields:** `x,y,size,direction`

### Make an attack
**POST**   /api/game/{game_id}/board/attack

**fields:** `x,y`




## Installation

```
cp .env.example .env

docker-compose up -d

docker-compose exec workspace bash

composer install

php artisan migrate

exit

chmod -R 777 storage/

```