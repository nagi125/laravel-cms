<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class Navi
 * @package App\Enums
 */
final class Navi extends Enum
{
    const ITEMS = [
        '/' => [
            'route' => 'top',
            'name'  => 'トップ',
        ],

        'contact' => [
            'route' => 'contact',
            'name'  => 'お問い合わせ',
        ],

        'admin' => [
            'route' => 'login',
            'name'  => '管理画面',
        ]
    ];
}
