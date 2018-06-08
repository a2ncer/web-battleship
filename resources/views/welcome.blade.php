<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Battle Ship Demo</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">


    <style>
        .grid-container {
            display: grid;
            grid-template-columns: @for($y=0; $y<=$dimension; $y++) {{" auto "}} @endfor;
            background-color: #2196F3;
            padding: 10px;
            width: 500px;
        }

        .grid-item {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.8);
            padding: 5px;
            font-size: 20px;
            text-align: center;

        }

        .board-container {
            margin-top: 20px;
            float: left;
        }

        button {
            width: 150px;
            height:50px;
        }

        .control-panel {
            display: grid;
            grid-template-columns: 250px 250px 220px;

            grid-gap: 5px 1px;
            background-color: rgba(240, 240, 255, 0.5);
            padding: 10px;
            width: 1000px;
        }

        .log {
            width:1000px;
        }

        [data-area="3"] {
            grid-area: 2 / 1 / 2 / 1;
        }

        [data-area="4"] {
            grid-area: 2 / 2 / 2 / 2;
        }

        [data-area="5"] {
            grid-area: 3 / 1 / 3 / 1;
        }

        [data-area="6"] {
            grid-area: 3 / 2 / 3 / 2;
        }

        [data-area="7"] {
            grid-area: 2 / 3 / 4 / 3;
        }

        [data-area="1"] {
            grid-area: 1 / 1 / 1 / 3;
        }

        [data-area="2"] {
            grid-area: 1 / 3 / 1 / 3;
        }

        [data-area="8"] {
            grid-area: 4 / 1 / 4 / 3;
        }

        [data-area="9"] {
            grid-area: 4 / 3 / 4 / 3;
        }



    </style>

</head>
<body>

<div class="log">

    <textarea style="width:100%; height:100px" id="log"></textarea>
</div>
<div class="content">
    <div class="control-panel">
        <div data-area="3">
            start x:<input id="ship_x" type="number"/>
        </div>
        <div data-area="4">

            size:
            <select id="ship_size" type="text">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div data-area="5">
            start y:<input id="ship_y" type="number"/>
        </div>
        <div data-area="6">
            direction:
            <select id="ship_direction" type="text">
                <option value="left">left</option>
                <option value="right">right</option>
                <option value="up">up</option>
                <option value="down">down</option>
            </select>
        </div>
        <div data-area="7">
            <button id="add-ship">Add Ship</button>
        </div>
        <div data-area="1">
            <h2>Game Id:<span id="id_game"></span></h2>
        </div>
        <div data-area="2">
            <button id="new-game">Start new Game</button>
        </div>
        <div data-area="8">
            Game Id:<input id="game_id" type="text"/>
        </div>
        <div data-area="9">
            <button id="join-game">Join</button>
        </div>
    </div>





    <div class="board-container">
        <h2>My Board</h2>
        @include("_parts.board",["boardType"=>\App\Services\Enums\SenderType::OWNER])

    </div>

    <div class="board-container">

        <h2>Opponent's Board</h2>
        @include("_parts.board",["boardType"=>\App\Services\Enums\SenderType::OPPONENT])

    </div>


</div>


<script>

    var app = {


        game_id: null,


        setGameId: function (id) {

            this.game_id = id;

            document.querySelector("#id_game").innerHTML = this.game_id;

        },


        ajax: function (method, url, data, func, errorFunc) {
            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function () {

                if (this.readyState === 4 && this.status === 200)
                    func(this.responseText);
                else errorFunc(this.readyState, this.status, this.responseText);
            };

            xhttp.open(method, url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(this.serialize(data));

        },


        ajaxError: function (state, status, response) {
            if (state === 4 && status !== 200)
            {
                console.log(state, status, response);
                app.log(response);
            }
        },


        getSession: function () {

            this.ajax("GET", "/api/session", null, function (data) {

                app.log("Session:"+data);
                console.log(data);

            }, this.ajaxError);
        },


        apiNewGame: function () {

            var app = this;

            this.ajax("POST", "/api/game/create", null, function (data) {

                app.log(data);

                data = JSON.parse(data);


                app.setGameId(data.id);


                console.log("Game Id:" + app.game_id);

                console.log(data);

            }, this.ajaxError);


        },

        apiJoinGame: function (gameId) {

            var app = this;

            this.ajax("PATCH", "/api/game/" + gameId + "/join", null, function (data) {

                app.log(data);

                data = JSON.parse(data);

                if (data.joined) {

                    app.setGameId(data.game.id);


                    console.log("Joined Game Id:" + app.game_id);
                }

                console.log(data);

            }, this.ajaxError);


        },

        apiAttack: function (x, y) {


            var app = this;

            var data = {
                x: x,
                y: y
            };

            this.ajax("POST", "/api/game/" + this.game_id + "/board/attack", data, function (data) {

                app.log(data);

                data = JSON.parse(data);

                console.log(obj);





                //app.renderMap("OWNER",data["board"]);

            }, this.ajaxError);

        },


        serialize: function (obj) {
            if (obj === null) {
                return null;
            }

            return Object.keys(obj).reduce(function (a, k) {
                a.push(k + '=' + encodeURIComponent(obj[k]));
                return a
            }, []).join('&')
        },

        apiAddShip: function () {

            var app = this;

            var data = {
                x:   document.querySelector("#ship_x").value,
                y:   document.querySelector("#ship_y").value,
                size:  document.querySelector("#ship_size").value,
                direction:  document.querySelector("#ship_direction").value
            };

            this.ajax("POST", "/api/game/" + this.game_id + "/board/addShip", data, function (data) {

                app.log(data);

                data = JSON.parse(data);

                app.renderMap("OWNER", data["board"]);

            }, this.ajaxError);

        },


        apiGetUpdates: function () {

            var app = this;

            if (this.game_id === null)
                return null;

            this.ajax("GET", "/api/game/" + this.game_id + "/updates", null, function (data) {

                data = JSON.parse(data);

                app.renderMap("OWNER", data["board"]);
                app.renderMap("OPPONENT", data["opponentBoard"]);

            }, this.ajaxError);
        },


        renderMap: function (boardType, map) {

            map.forEach(function (element, y) {

                element.forEach(function (value, x) {

                    if (value !== 0)
                        document.querySelector("#" + boardType + "_y" + y + "_x" + x).innerHTML = value;

                });

            });
        },


        startUpdates: function () {

            var app = this;

            setInterval(function () {

                app.apiGetUpdates();

            }, 1000);

        },


        log: function (message) {

           document.querySelector("#log").value += message+"\n";

        },


        init: function () {


            var app = this;

            var boardCells = document.querySelectorAll(".board_cell");

            Array.from(boardCells).forEach(function (element) {
                element.addEventListener('click', function (e) {

                    var x = element.dataset.x;
                    var y = element.dataset.y;
                    var type = element.dataset.type;


                    if (type === "OPPONENT") {
                        app.apiAttack(x, y);
                    }
                    else {
                        document.querySelector("#ship_x").value = x;
                        document.querySelector("#ship_y").value = y;
                    }

                    console.log({x: x, y: y, type: type});

                });
            });


            var newGameButton = document.querySelector("#new-game");

            newGameButton.addEventListener('click', function () {

                console.log("new game");

                app.apiNewGame();

            });


            var addShipButton = document.querySelector("#add-ship");

            addShipButton.addEventListener('click', function () {

                console.log("add ship");

                app.apiAddShip();

            });


            var joinGameButton = document.querySelector("#join-game");

            joinGameButton.addEventListener('click', function () {

                console.log("join game");

                var id = document.querySelector("#game_id").value;

                console.log("try join ..." + id);

                app.apiJoinGame(id);

            });


            this.getSession();

            this.startUpdates();

        }

    };


    app.init();


</script>
</body>
</html>