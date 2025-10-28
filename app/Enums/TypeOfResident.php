<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static ORANG_TUA()
 * @method static static WALI()
 * @method static static KOST()
 * @method static static ASRAMA()
 * @method static static PANTI_ASUHAN()
 */
final class TypeOfResident extends Enum
{
    const ORANG_TUA = 'orang tua';
    const WALI = 'wali';
    const KOST = 'kost';
    const ASRAMA = 'asrama';
    const PANTI_ASUHAN = 'panti asuhan';
}
