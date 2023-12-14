<?php

namespace App\Notifications\V1;

use App\Models\Admin\Support\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class SupportFirstMessage
 * @package App\Notifications\V1
 */
class SupportFirstMessage extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private string $local,
        private Ticket $ticket
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
        return (new MailMessage)->view('emails.support_first_message', [
            'user' => $notifiable,
            'local' => $this->local,
            'ticket' => $this->ticket,
        ])->subject(__('mails.SupportNewMessageTitle', [], $this->local));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_name' => $this->ticket->name,
            'ticket_user_name' => $this->ticket->user->full_name,
            'ticket_user_id' => $this->ticket->user_id,
        ];
    }
}
