<?php declare(strict_types=1);

namespace App\Enums\Chat;

use BenSampo\Enum\Enum;

/**
 * Class ChatTypesEnum
 * @package App\Enums\Chat
 */
final class ChatTypesEnum extends Enum
{
    /**
     * Type Support (тип поддержки)
     */
    const SUPPORT = 'support';

    /**
     * Type Resell Продаю (тип когда пишут пользователя)
     */
    const RESELL = 'resell';

    /**
     * Type Buying Покупаю (тип когда пользовател пишет)
     */
    const BUYING = 'buying';
}
