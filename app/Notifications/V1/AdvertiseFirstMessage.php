<?php

namespace App\Notifications\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class AdvertiseFirstMessage
 * @package App\Notifications\V1
 */
class AdvertiseFirstMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private string $message,
        private string $local,
        private ?\App\Models\Products\Advertise $advertise = null
    ) {
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail', 'database'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)->view('emails.advertise_first_message', [
            'user' => $notifiable,
            'message' => $this->message,
            'local' => $this->local,
            'advertise' => $this->advertise,
        ])->subject(__('mails.PublicationNewMessageTitle', [], $this->local));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        return [
            'message' => $this->message,
            'receiver_user_id' => $notifiable->id,
            'advertise_id' => $this->advertise->id,
            'advertise_name' => $this->advertise->name,
        ];
    }
}
