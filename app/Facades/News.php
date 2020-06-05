<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class User
 * @package App\Facades
 */
class News extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'news';
    }

}