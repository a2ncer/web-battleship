<?php


namespace App\Services\DTO;


use Illuminate\Http\Request;

class Ship
{

    private $startX;
    private $startY;
    private $size;
    private $direction;

    public function map(Request $request)
    {
        $this->startX = $request->input("x");
        $this->startY = $request->input("y");
        $this->size = $request->input("size");
        $this->direction = $request->input("direction");

        return $this;
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
}