<?php declare(strict_types=1);

namespace App\Enums\Advertise;

use BenSampo\Enum\Enum;

/**
 * @method static static FREE()
 * @method static static PAID()
 * @method static static EXCHANGE()
 * @method static static CONTRACTUAL()
 */
final class AdvertisePricePolicy extends Enum
{
    /**
     * Ценовая политика - бесплатное
     */
    const FREE = 0;

    /**
     * Ценовая политика - платное
     */
    const PAID = 1;

    /**
     * Ценовая политика - обмен
     */
    const EXCHANGE = 2;

    /**
     * Ценовая политика - «Договорная»
     */
    const CONTRACTUAL = 3;
}
