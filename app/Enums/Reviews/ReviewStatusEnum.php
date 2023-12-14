<?php declare(strict_types=1);

namespace App\Enums\Reviews;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * Class ReviewStatusEnum
 *
 * Статус сделки - При публикации отзыва, Пользователь обязан указать статус сделки.
 * Статусы будут следующие  : 1 - Сделка состоялась, 2 - Сделка сорвалась. 3 - Не общались..
 *
 */
final class ReviewStatusEnum extends Enum implements LocalizedEnum {

    /**
     * Не общались..
     */
    const Default = 0;

    /**
     * Сделка состоялась
     */
    const Success = 1;

    /**
     * Сделка сорвалась
     */
    const Fail = 2;
}
