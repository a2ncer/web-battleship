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
            float:left;
        }
    </style>

</head>
<body>


<div class="content">

    <div class="control-panel">

        <div>
        <button id="new-game">New</button>
        </div>

        <div>
        <button id="add-ship">Add Ship</button>
        </div>

        <div>
            <input id="game_id" type="text"/>
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
                console.log(state, status, response);
        },


        getSession: function () {

            this.ajax("GET", "/api/session", null, function (data) {

                console.log(data);

            }, this.ajaxError);
        },


        apiNewGame: function () {

            this.ajax("POST", "/api/game/create", null, function (data) {

                data = JSON.parse(data);

                app.game_id = data.id;

                console.log("Game Id:"+app.game_id);

                console.log(data);

            }, this.ajaxError);


        },

        apiJoinGame: function (gameId) {

            this.ajax("PATCH", "/api/game/" + gameId + "/join", null, function (data) {

                data = JSON.parse(data);

                app.game_id = data.game.id;

                console.log("Joined Game Id:"+app.game_id);

                console.log(data);

            }, this.ajaxError);


        },

        apiAttack: function(x,y) {


            var app = this;

            var data = {
                x: x,
                y: y
            };

            this.ajax("POST", "/api/game/" + this.game_id + "/board/attack", data, function (data) {

                data = JSON.parse(data);

                console.log(data);

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
                x: 1,
                y: 2,
                size: 3,
                direction: "down"
            };

            this.ajax("POST", "/api/game/" + this.game_id + "/board/addShip", data, function (data) {

                data = JSON.parse(data);

                app.renderMap("OWNER",data["board"]);

            }, this.ajaxError);

        },


        apiGetUpdates: function(){

            var app = this;

            if(this.game_id === null)
                return null;

            this.ajax("GET", "/api/game/" + this.game_id + "/updates", null, function (data) {

                data = JSON.parse(data);

                app.renderMap("OWNER",data["board"]);
                app.renderMap("OPPONENT",data["opponentBoard"]);

            }, this.ajaxError);
        },


        renderMap: function(boardType, map)
        {

            map.forEach(function (element, y){

                    element.forEach(function (value, x) {

                        if(value!==0)
                        document.querySelector("#"+boardType+"_y"+y+"_x"+x).innerHTML = value;

                    });

            });
        },



        startUpdates: function () {

            var app = this;

            setInterval(function(){

                app.apiGetUpdates();

            },1000);

        },


        init: function () {


            var app = this;

            var boardCells = document.querySelectorAll(".board_cell");

            Array.from(boardCells).forEach(function (element) {
                element.addEventListener('click', function (e) {

                    var x = element.dataset.x;
                    var y = element.dataset.y;
                    var type = element.dataset.type;


                    if(type === "OPPONENT")
                    {
                        app.apiAttack(x,y);
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

                var id =  document.querySelector("#game_id").value;

                console.log("try join ..."+id);

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