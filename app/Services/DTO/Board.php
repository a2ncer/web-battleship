<?php

namespace App\Services\DTO;

use Illuminate\Support\Collection;

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

    private function getClearBoard()
    {
        $board = [];

        for ($y = 0; $y < $this->dimension; ++$y) {
            for ($x = 0; $x < $this->dimension; ++$x) {
                $board[$y][$x] = 0;
            }
        }

        return $board;
    }

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
            $this->board[$record->getX()][$record->getY()] = $record->getEvent();
        }

        return $this;
    }

    public function addShip(Ship $ship)
    {
        if (isset($this->board[$ship->getStartX()][$ship->getStartY()])) {
            if ($this->board[$ship->getStartX()][$ship->getStartY()] === 0) {
                $shipMap = $this->tryAddShip($ship);

                if ($shipMap) {
                    $this->board = array_replace($this->board, $shipMap);
                } else {
                    dd('can not add ship');
                }
            } else {
                dd('This cell is not free');
            }
        } else {
            dd('Out of range');
        }

        return $this;
    }

    private function tryAddShip(Ship $ship)
    {
        $result = $this->getClearBoard();

        $x = $ship->getStartX();
        $y = $ship->getStartY();
        $size = $ship->getSize();

        if ($size > $this->dimension || $size < 0) {
            return false;
        }

        if ($ship->getDirection() === 'up') {
            for ($y1 = $y; $y1 > $y - $size; --$y1) {
                if (isset($this->board[$y1][$x]) && $this->board[$y1][$x] === 0) {
                    $result[$y1][$x] = 1;
                } else {
                    return false;
                }
            }
        }

        if ($ship->getDirection() === 'down') {
            for ($y1 = $y; $y1 < $y + $size; ++$y1) {
                if (isset($this->board[$y1][$x]) && $this->board[$y1][$x] === 0) {
                    $result[$y1][$x] = 1;
                } else {
                    return false;
                }
            }
        }

        if ($ship->getDirection() === 'left') {
            for ($x1 = $x; $x1 > $x - $size; --$x1) {
                if (isset($this->board[$y][$x1]) && $this->board[$y][$x1] === 0) {
                    $result[$y][$x1] = 1;
                } else {
                    return false;
                }
            }
        }

        if ($ship->getDirection() === 'right') {
            for ($x1 = $x; $x1 < $x + $size; ++$x1) {
                if (isset($this->board[$y][$x1]) && $this->board[$y][$x1] === 0) {
                    $result[$y][$x1] = 1;
                } else {
                    return false;
                }
            }
        }

        return $result;
    }
}
