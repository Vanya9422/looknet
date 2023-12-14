<?php

namespace App\Notifications\V1;

use App\Notifications\V1\Channels\SmsTwilioChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * Class SmsConfirmation
 * @package App\Notifications\V1
 */
class SmsConfirmation
    extends Notification
    implements NotificationsTypesContract, ShouldQueue
{
    use Queueable;

    /**
     * @var int $code
     */
    private int $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        private int $code_type = 0,
        public ?string $phone = null,
        private ?string $local = null,
    ) {
        $this->code = mt_rand(100000, 999999);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsTwilioChannel::class, 'database'];
    }

    /**
     * @param $notifiable
     */
    public function toSmsTwilio($notifiable): void {
        $logMessage = sprintf("Sending code '%s' to phone number '%s'", $this->code, $notifiable->phone);
        \Log::info($logMessage);

        \Twilio::message($this->phone, $this->code);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'code' => $this->code,
            'phone' => $this->phone
        ];
    }
}
