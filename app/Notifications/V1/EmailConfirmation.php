<?php

namespace App\Notifications\V1;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Utils\Translates\EnumTranslator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class EmailConfirmation
 * @package App\Notifications
 */
class EmailConfirmation extends Notification implements ShouldQueue, NotificationsTypesContract {

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
        public ?string $email = null,
        private ?string $local = null,
    ) {
        $this->code = mt_rand(100000, 999999);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array {
        $logMessage = sprintf("Sending code '%s' to email '%s'", $this->code, $this->email);

        \Log::info($logMessage);

        return ['mail', 'database'];
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage {
        $translations = EnumTranslator::translate(SendCodeContentTextEnum::class,null, $this->local);

        return (new MailMessage)
            ->view('emails.send-code', [
                'code' => $this->code,
                'user' => $notifiable,
                'content_text' => $translations[$this->code_type],
                'local' => $this->local,
            ])->subject(__('mails.ConfirmCode', [], $this->local));
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function toArray($notifiable): array {
        return [
            'code' => $this->code,
            'email' => $this->email,
            'message' => sprintf("Sending code '%s' to email '%s'", $this->code, $this->email),
        ];
    }
}
