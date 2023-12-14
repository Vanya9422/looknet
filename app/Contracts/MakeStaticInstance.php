<?php

namespace App\Contracts;

/**
 * Interface MakeStaticInstance
 * @package App\Contracts
 */
interface MakeStaticInstance {

    /**
     * Create a new resource instance.
     *
     * @param  mixed  ...$parameters
     * @return static
     */
    public static function make(...$parameters): static;
}
