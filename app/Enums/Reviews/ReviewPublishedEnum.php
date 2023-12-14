<?php declare(strict_types=1);

namespace App\Enums\Reviews;

use BenSampo\Enum\Enum;

/**
 * Class ReviewStatusEnum
 * когда создается отзыв  сначала published становится 0 и отзыв не показывается в саите после того когда
 * модератор принимает published становится 1 если принял если отказал 2
 */
final class ReviewPublishedEnum extends Enum
{
    /**
     * Нови отзив или обновленни
     */
    const NewReviewed = 0;

    /**
     * Отзив Принято Модератором оно может показатся везде
     */
    const Success = 1;

    /**
     * Отзив Откланено Модертором
     */
    const Reject = 2;
}
