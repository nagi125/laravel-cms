<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Utility
 * @package app\Facades
 */
class Utility extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'utility';
    }

}