<?php

namespace App\Http\Middleware\Api\V1;

use App\Enums\Users\UserSettingsChangeEmailOrPhoneTypesEnum;
use App\Models\Admin\Support\Notification;
use Closure;
use Illuminate\Http\Request;

/**
 * Class CheckAuthenticationType
 * @package App\Http\Middleware\Api\V1
 */
class CheckAuthenticationType
{
    /**
     * @var string|null
     */
    private ?string $confirmationValue = null;

    /**
     * @var string|null
     */
    private ?string $confirmationType = null;

    /**
     * @var bool
     */
    private bool $validPhone = false;

    /**
     * @var bool
     */
    private bool $validEmail = false;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed {
        $this->setProperties($request);

        if ($this->confirmationValue) {

            $this->setValuesValidatedRegexps();

            // Валидная почта
            if ($this->isValidEmail()) {
                $request->merge([
                    'confirmation_type' => Notification::Email_CONFIRMATION,
                    'email' => $this->confirmationValue,
                    'field' => 'email',
                    'event' => UserSettingsChangeEmailOrPhoneTypesEnum::isValidEmail,
                ]);
            }

            // валидни номер
            if ($this->isValidPhone()) {
                $request->merge([
                    'confirmation_type' => Notification::SMS_CONFIRMATION,
                    'phone' => $this->confirmationValue,
                    'field' => 'phone',
                    'event' => UserSettingsChangeEmailOrPhoneTypesEnum::isValidPhone,
                ]);
            }

            // добовляет или меняет почту а код должен отправится на номер
            if ($this->isValidEmailCodeSendToPhone()) {
                $request->merge([
                    'confirmation_type' => Notification::SMS_CONFIRMATION,
                    'email' => $this->confirmationValue,
                    'field' => 'email',
                    'event' => UserSettingsChangeEmailOrPhoneTypesEnum::isValidEmailCodeSendToPhone,
                ]);
            }

            // добовляет или меняет номер а код должен отправится на почту
            if ($this->isValidPhoneCodeSendToEmail()) {
                $request->merge([
                    'confirmation_type' => Notification::Email_CONFIRMATION,
                    'phone' => $this->confirmationValue,
                    'field' => 'phone',
                    'event' => UserSettingsChangeEmailOrPhoneTypesEnum::isValidPhoneCodeSendToEmail
                ]);
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return void
     */
    private function setProperties(Request $request): void {
        $this->confirmationValue = $request->get('confirmation_auth');
        $this->confirmationType = $request->get('confirmation_type');
    }

    /**
     * @return void
     */
    private function setValuesValidatedRegexps(): void {
        // Номер соответствует формату (от 10 до 15 цифр без +)
        $this->validPhone = preg_match('/^\d{10,15}$/', $this->confirmationValue);

        $this->validEmail = preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $this->confirmationValue);
    }

    /**
     * @return bool
     */
    private function isValidPhone(): bool {
        return ($this->validPhone && !$this->confirmationType)
            || ($this->validPhone && $this->confirmationType === 'phone');
    }

    /**
     * @return bool
     */
    private function isValidEmail(): bool {
        return ($this->validEmail && !$this->confirmationType)
            || ($this->validEmail && $this->confirmationType === 'email');
    }

    /**
     * @return bool
     */
    private function isValidEmailCodeSendToPhone(): bool {
        return $this->validEmail && $this->confirmationType === 'phone';
    }

    /**
     * @return bool
     */
    private function isValidPhoneCodeSendToEmail(): bool {
        return $this->validPhone && $this->confirmationType === 'email';
    }
}
