<?php

namespace App\Exceptions\Users\Settings;

use Exception;

class SendCodeEventNotFoundException extends Exception {

    /**
     * @return static
     */
    public static function eventNotProvided(): static {
        return new static('Send Code Event Not Found Error');
    }
}
