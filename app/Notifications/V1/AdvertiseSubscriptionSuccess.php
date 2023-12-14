<?php

namespace App\Notifications\V1;

use App\Models\Payment\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class AdvertiseSubscriptionSuccess
 * @package App\Notifications\V1
 */
class AdvertiseSubscriptionSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private Subscription $subscription,
        private \App\Models\Products\Advertise $advertise
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
        return ['mail'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage {
        return (new MailMessage)->view('emails.advertise_payment_success', [
            'user' => $notifiable,
            'advertise' => $this->advertise
        ])->subject(__('mails.PublicationPaymentSuccessTitle')); // добавляем тему письма;
    }
}
