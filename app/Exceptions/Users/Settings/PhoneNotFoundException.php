<?php

namespace App\Exceptions\Users\Settings;

/**
 * Class PhoneNotFoundException
 * @package App\Exceptions
 */
class PhoneNotFoundException extends \Exception
{
    /**
     * @return static
     */
    public static function phoneNotSetForEmailChange(): static {
        return new static(__('messages.USER_PHONE_NOT_FOUND'));
    }
}
