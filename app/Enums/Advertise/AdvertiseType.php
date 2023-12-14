<?php declare(strict_types=1);

namespace App\Enums\Advertise;

use BenSampo\Enum\Enum;

/**
 * @method static static REGULAR()
 * @method static static RAISE()
 * @method static static TOP()
 * @method static static VIP()
 */
final class AdvertiseType extends Enum
{
    /**
     * Обычные Регулярные объявления
     */
    const REGULAR = 0;

    /**
     * Поднятие
     *
     * С этой услугой ваше объявление будет отображаться с периодическим повтором в результатах поиска вместе с
     * другими объявлениями, размещенными в ТОП в той же категории.
     */
    const RAISE = 1;

    /**
     * TOP-объявление
     * Размещение в ТОП
     *
     * С этой услугой ваше объявление будет отображаться с периодическим повтором в результатах поиска вместе с
     * другими объявлениями, размещенными в ТОП в той же категории.
     */
    const TOP = 2;

    /**
     * VIP-объявление
     *
     * С этой услугой объявление отображается с периодическим повтором на главной странице
     */
    const VIP = 3;
}
