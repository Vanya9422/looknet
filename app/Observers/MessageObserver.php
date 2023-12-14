<?php

namespace App\Observers;

use App\Events\Chat\MessageUnreadCounts;
use App\Models\Chat\Message;

/**
 * Class MessageObserver
 * @package App\Observers
 */
class MessageObserver {

    /**
     * Handle the Advertise "created" event.
     *
     * @param \App\Models\Chat\Message $message
     * @return void
     */
    public function created(Message $message) {
         broadcast(MessageUnreadCounts::make($message->conversation, user()->id))->toOthers();
    }
}
