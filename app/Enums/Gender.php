<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static MALE()
 * @method static static FEMALE()
 */
final class Gender extends Enum
{
    const MALE = 'Male';
    const FEMALE = 'Female';
}
