<?php

namespace App\Services\DTO;

use App\Services\Enums\CellType;
use App\Services\Enums\MoveType;
use Illuminate\Support\Collection;
use Mockery\Exception;

class Board
{
    private $board = [];
    private $dimension;

    /**
     * Board constructor.
     *
     * @param int $dimension
     */
    public function __construct($dimension = 10)
    {
        $this->dimension = $dimension;
        $this->board = $this->getClearBoard();
    }

    /**
     * @return array
     */
    private function getClearBoard()
    {
        $board = [];

        for ($y = 0; $y < $this->dimension; ++$y) {
            for ($x = 0; $x < $this->dimension; ++$x) {
                $board[$y][$x] = CellType::FREE;
            }
        }

        return $board;
    }

    /**
     * @return array
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @param Collection $moves
     *
     * @return $this
     */
    public function map(Collection $moves)
    {
        foreach ($moves as $record) {
            $y = $record->getY();
            $x = $record->getX();

            $value = $this->board[$y][$x];

            $status = $this->convertEventToCell($value, $record->getEvent());

            $this->board[$y][$x] = $status;
        }

        return $this;
    }

    /**
     * @param $value
     * @param $event
     *
     * @return string
     */
    public function convertEventToCell($value, $event)
    {
        if ($value === CellType::SHIP && $event === MoveType::ATTACK) {
            return CellType::HIT;
        }

        if ($value === CellType::FREE && $event === MoveType::ATTACK) {
            return CellType::MISS;
        }

        if ($value === CellType::FREE && $event === MoveType::ADD_SHIP) {
            return CellType::SHIP;
        }

        throw new Exception("Couldn't make this action");

    }

    /**
     * @param Point $point
     *
     * @return bool
     */
    public function attack(Point $point)
    {
        $y = $point->getY();
        $x = $point->getX();

        if (isset($this->board[$y][$x])) {
            $value = $this->board[$y][$x];
            $status = $this->convertEventToCell($value, MoveType::ATTACK);


            $this->board[$y][$x] = $status;

            if ($status === CellType::HIT) {
                return true;
            }

            if ($status === CellType::MISS) {
                return false;
            }


            throw new Exception("You have already try this cell");

        } else throw new Exception("Out of range");


    }

    /**
     * @param Ship $ship
     *
     * @return $this
     */
    public function addShip(Ship $ship)
    {
        if (isset($this->board[$ship->getStartY()][$ship->getStartX()])) {
            if ($this->board[$ship->getStartY()][$ship->getStartX()] === CellType::FREE) {
                $shipMap = $this->tryAddShip($ship);

                if ($shipMap) {
                    $this->board = array_replace($this->board, $shipMap);
                } else {
                    throw new Exception('You can not add a ship');
                }
            } else {
                throw new Exception('The start cell is occupied');
            }
        } else {
            throw new Exception('Out of range');
        }

        return $this;
    }

    /**
     * @param Ship $ship
     *
     * @return array|bool
     */
    private function tryAddShip(Ship $ship)
    {
        $result = $this->board;

        $size = $ship->getSize();

        if ($size > $this->dimension || $size < 0) {
            return false;
        }

        /** @var Point $point */
        foreach ($ship->getCoordinates() as $point) {
            $y = $point->getY();
            $x = $point->getX();

            if (isset($this->board[$y][$x]) && $this->board[$y][$x] === CellType::FREE) {
                $result[$y][$x] = CellType::SHIP;
            }
        }

        return $result;
    }

    public function withOutShips()
    {
        return array_map(function ($y) {
            return array_map(function ($x) {
                return $x === CellType::SHIP ? CellType::FREE : $x;
            },$y);

        }, $this->board);


    }
}
