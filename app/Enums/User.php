<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Class User
 * @package App\Enums
 */
final class User extends Enum
{
    const ROLES = [
        1 => 'Master',
        2 => 'Standard',
        3 => 'Light',
    ];

    const ROLES_TEXT = [
        'Master'   => 1,
        'Standard' => 2,
        'Light'    => 3,
    ];
}
