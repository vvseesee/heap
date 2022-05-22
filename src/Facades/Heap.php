<?php

namespace Vvseesee\Heap\Facades;

use Illuminate\Support\Facades\Facade;
use Vvseesee\Heap\Heap as Accessor;

class Heap extends Facade
{
    public static function getFacadeAccessor()
    {
        return Accessor::class;
    }
}
