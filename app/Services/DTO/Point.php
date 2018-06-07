<?php

namespace App\Services\DTO;


use Illuminate\Http\Request;
use JsonSerializable;

class Point implements JsonSerializable
{
    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * Point constructor.
     *
     * @param null $y
     * @param null $x
     */
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

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function map(Request $request)
    {
        $this->x = $request->input('x');
        $this->y = $request->input('y');

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource.
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return ["x"=>$this->x, "y"=>$this->y];
    }
}
