<?php

namespace App\Services\DTO;


use Illuminate\Http\Request;

class Point
{
    private $x;
    private $y;

    public function __construct($y = null, $x = null)
    {
        $this->y = $y;
        $this->x = $x;
    }


    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }


    public function map(Request $request)
    {
        $this->x = $request->input('x');
        $this->y = $request->input('y');

        return $this;
    }


}