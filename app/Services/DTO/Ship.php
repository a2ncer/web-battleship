<?php

namespace App\Services\DTO;

use Illuminate\Http\Request;

class Ship
{
    private $startX;
    private $startY;
    private $size;
    private $direction;
    private $coordinates;

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function map(Request $request)
    {
        $this->startX = $request->input('x');
        $this->startY = $request->input('y');
        $this->size = $request->input('size');
        $this->direction = $request->input('direction');
        $this->generateCoordinates();

        return $this;
    }


    private function generateCoordinates()
    {
        if ($this->getDirection() === 'up') {
            for ($y1 = $this->startY; $y1 > $this->startY - $this->size; --$y1) {
                $this->coordinates[] = new Point($y1, $this->startX);
            }
        }

        if ($this->getDirection() === 'down') {
            for ($y1 = $this->startY; $y1 < $this->startY + $this->size; ++$y1) {
                $this->coordinates[] = new Point($y1, $this->startX);
            }
        }

        if ($this->getDirection() === 'left') {
            for ($x1 = $this->startX; $x1 > $this->startX - $this->size; --$x1) {
                $this->coordinates[] = new Point($this->startY, $x1);
            }
        }

        if ($this->getDirection() === 'right') {
            for ($x1 = $this->startX; $x1 < $this->startX + $this->size; ++$x1) {
                $this->coordinates[] = new Point($this->startY, $x1);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getStartX()
    {
        return $this->startX;
    }

    /**
     * @return mixed
     */
    public function getStartY()
    {
        return $this->startY;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @return mixed
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }
}
