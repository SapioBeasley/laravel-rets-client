<?php namespace Lara\Rets\Facades;

use Illuminate\Support\Facades\Facade;

class Rets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App::make('rets');

    }
}