<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static PENDING_VERIFICATION()
 * @method static static VERIFIED()
 * @method static static REJECTED()
 */
final class RegistrationStatus extends Enum
{
    const PENDING_VERIFICATION = 'Pending Verification';
    const VERIFIED = 'Verified';
    const REJECTED = 'Rejected';
}
