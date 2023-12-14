<?php

namespace App\Notifications\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class Advertise
 * @package App\Notifications\V1
 */
class Advertise extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private array $data,
        private ?string $subject = null,
        private ?string $text = null,
        private ?\App\Models\Products\Advertise $advertise = null,
        private ?string $local = null,
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
        return (new MailMessage)->view('emails.advertise_accepted_or_rejected', [
            'user' => $notifiable,
            'advertise' => $this->advertise,
            'title' => $this->subject,
            'content_text' => $this->text,
            'local' => $this->local,
        ])->subject($this->subject); // добавляем тему письма;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return $this->data;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        return $this->data;
    }
}
