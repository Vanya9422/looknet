<?php declare(strict_types=1);

namespace App\Enums\Mails;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * Класс перечисление, определяющий константы для типов кодов подтверждения, используемых в электронной почте.
 *
 * Этот класс использует библиотеку BenSampo/Enum для определения перечислений,
 * что позволяет более структурированно и безопасно работать с константами.
 *
 * @package App\Enums\Mails
 */
final class SendCodeContentTextEnum extends Enum implements LocalizedEnum
{
    /**
     * Код подтверждения для регистрации.
     *
     * Используется, когда пользователь регистрируется в системе и ему необходимо подтвердить свой аккаунт.
     */
    public const REGISTER_CONFIRMATION_CODE = 0;

    /**
     * Код подтверждения для сброса пароля.
     *
     * Применяется, когда пользователь инициирует процесс восстановления забытого пароля.
     */
    public const RESET_PASS_CONFIRMATION_CODE = 1;

    /**
     * Код подтверждения для изменения контактных данных
     *
     * Используется при изменении пользователем своих контактных данных, таких как электронная почта или номер телефона.
     */
    public const CHANGE_EMAIL_OR_PHONE_SETTINGS_CONFIRMATION_CODE = 2;

    /**
     * Код подтверждения для изменения пароля.
     *
     * Применяется, когда пользователь изменяет свой текущий пароль на новый.
     */
    public const CHANGE_PASSWORD_CONFIRMATION_CODE = 3;

    /**
     * Возвращает ключ локализации для значения перечисления.
     *
     * @param mixed $value Значение перечисления.
     * @return string Ключ локализации для значения перечисления.
     */
    public static function getTranslationKey(mixed $value): string
    {
        return static::getLocalizationKey() . '.' . $value;
    }
}
