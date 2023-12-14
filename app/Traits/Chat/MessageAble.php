<?php

namespace App\Traits\Chat;

use App\Exceptions\Chat\InvalidDirectMessageNumberOfParticipants;
use App\Models\Chat\Conversation;
use App\Models\Chat\Participation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait Messageable
 * @package App\Traits\Chat
 */
trait MessageAble
{
    public function conversations()
    {
        return $this->participation->pluck('conversation');
    }

    /**
     * @return MorphMany
     */
    public function participation(): MorphMany
    {
        return $this->morphMany(Participation::class, 'messageable');
    }

    /**
     * @param \App\Models\Chat\Conversation $conversation
     * @throws InvalidDirectMessageNumberOfParticipants
     */
    public function joinConversation(Conversation $conversation)
    {
        if ($conversation->isDirectMessage() && $conversation->participants()->count() == 2) {
            throw new InvalidDirectMessageNumberOfParticipants();
        }

        $participation = new Participation([
            'messageable_id'   => $this->getKey(),
            'messageable_type' => $this->getMorphClass(),
            'conversation_id'  => $conversation->getKey(),
        ]);

        $this->participation()->save($participation);
    }

    public function leaveConversation($conversationId)
    {
        $this->participation()->where([
            'messageable_id'   => $this->getKey(),
            'messageable_type' => $this->getMorphClass(),
            'conversation_id'  => $conversationId,
        ])->delete();
    }
}
