<?php


namespace App\Services\Enums;


abstract class CellType
{

    const FREE = 0;
    const SHIP = '*';
    const MISS = '.';
    const HIT = 'x';
}