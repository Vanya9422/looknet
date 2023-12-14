<?php declare(strict_types=1);

namespace App\Enums\Refusal;

use BenSampo\Enum\Enum;

/**
 * Class RefusalTypeEnum
 */
final class RefusalTypeEnum extends Enum
{
    /**
     * Тип 0 это Виды Текста Для Модераторов когда отклоняют объявление
     */
    const RejectTypeAdvertise = 0;

    /**
     * Тип 1 Это типы Жалоб Для Чата Пользователей
     */
    const ComplaintTypeChat = 1;

    /**
     * Тип 2 Это типы текстов для жалоби на отзиви публикации
     */
    const ComplaintTypeReviews = 2;
}
