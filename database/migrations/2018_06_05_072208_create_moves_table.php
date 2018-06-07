<?php

use App\Services\Enums\MoveType;
use App\Services\Enums\SenderType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("game_id");
            $table->enum("event", [MoveType::ADD_SHIP, MoveType::ATTACK]);
            $table->enum("sender", [SenderType::OWNER, SenderType::OPPONENT]);
            $table->integer("x");
            $table->integer("y");
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moves');
    }
}
