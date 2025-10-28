<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static SMA()
 * @method static static SMK()
 * @method static static MA()
 * @method static static D2_D3_D4()
 * @method static static UNIV_PT()
 */
final class SchoolType extends Enum
{
    const SMA = 'sma';
    const SMK = 'smk';
    const MA = 'ma';
    const D2_D3_D4 = 'd2/d3/d4';
    const UNIV_PT = 'Univ/PT';
}
