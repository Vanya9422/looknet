<?php

namespace App\Contracts;

use App\Models\User;

/**
 * Interface UserSettingsCodeConfirmationHandlerInterface
 * @package App\Contracts
 */
interface UserSettingsCodeConfirmationHandlerInterface {

    /**
     * @param User $user
     * @param array $data
     * @return void
     */
    public function handle(User $user, array $data): void;
}
