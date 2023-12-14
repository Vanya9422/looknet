<?php

namespace App\Observers;

use App\Models\Users\SocialAccount;
use App\Models\User;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver {
    /**
     * Handle the User "updated" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updated(User $user) {
        $this->deleteProviderEmailIfChangedUserEmail($user);
    }

    /**
     * Проверяет если у пользователя нет порол при регистрации или
     *
     * @param \App\Models\User $user
     * @return void
     */
    protected function deleteProviderEmailIfChangedUserEmail(User $user): void {
        if ($user->isDirty('email')) {
            SocialAccount::where([
                'user_id' => $user->id,
                'provider_email' => $user->getOriginal('email'),
            ])->delete();
        }
    }
}
