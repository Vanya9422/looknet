<?php

namespace App\Http\Requests\V1\Users;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Http\Requests\V1\FormRequest;
use App\Models\Admin\Support\Notification;
use Illuminate\Validation\Rule;

/**
 * Class UpdateAuthTypesRequest
 * @property ?string $event
 * @package App\Http\Requests\V1\Users
 */
class UpdateAuthTypesRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        $rulesEmail = [
            Notification::Email_CONFIRMATION => [
                'email' => 'required|string|email|' . Rule::unique('users')->ignore(user()->id),
                'code_type' => ['required', 'integer', Rule::in(SendCodeContentTextEnum::getValues())]
            ],
            Notification::SMS_CONFIRMATION => [
                'phone' => 'required|string|max:15|regex:/^[0-9]+$/|' . Rule::unique('users')->ignore(user()->id),
                'phone_view' => 'nullable|string',
                'code_type' => ['required', 'integer', Rule::in(SendCodeContentTextEnum::getValues())]
            ],
        ];

        // добовляет или меняет почту а код должен отправится на номер
        if ($this->event === 'isValidEmailCodeSendToPhone') {
            return $rulesEmail[Notification::Email_CONFIRMATION];
        }

        // добовляет или меняет номер а код должен отправится на почту
        if ($this->event === 'isValidPhoneCodeSendToEmail') {
            return $rulesEmail[Notification::SMS_CONFIRMATION];
        }

        return $rulesEmail[$this->get('confirmation_type')];
    }
}
