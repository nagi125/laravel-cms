<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class User
 * @package App\Facades
 */
class User extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'user';
    }

}