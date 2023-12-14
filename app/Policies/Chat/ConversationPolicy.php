<?php

namespace App\Policies\Chat;

use App\Models\Chat\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ConversationPolicy
 * @package App\Policies\Chat
 */
class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Chat\Conversation $conversation
     * @return bool
     */
    public function update(User $user, Conversation $conversation): bool {
        return $user->id === $conversation->starter_id;
    }
}
