<?php

namespace App\Exceptions\Users\Settings;

/**
 * Class EmailNotFoundException
 * @package App\Exceptions
 */
class EmailNotFoundException extends \Exception
{
    /**
     * @return static
     */
    public static function emailNotSetForPhoneChange(): static {
        return new static(__('messages.USER_EMAIL_NOT_FOUND'));
    }
}

