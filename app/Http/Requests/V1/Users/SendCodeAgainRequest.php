<?php

namespace App\Http\Requests\V1\Users;

use App\Enums\Mails\SendCodeContentTextEnum;
use App\Http\Requests\V1\FormRequest;
use App\Models\Admin\Support\Notification;
use App\Traits\FormRequestAuthorizeAble;
use Illuminate\Validation\Rule;

/**
 * Class SendCodeAgainRequest
 * @package App\Http\Requests\V1\Users
 */
class SendCodeAgainRequest extends FormRequest {

    use FormRequestAuthorizeAble;

    /**
     * @var array|string[]
     */
    protected array $rulesEmail = [
        Notification::Email_CONFIRMATION => [
            'email' => 'required|string|email|exists:users,email',
        ],
        Notification::SMS_CONFIRMATION => [
            'phone' => 'required|regex:/^\d{10,15}$/|exists:users,phone',
        ],
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        $rules = $this->rulesEmail[$this->get('confirmation_type')];

        return array_merge($rules, ['code_type' => [
            'required', 'integer', Rule::in(SendCodeContentTextEnum::getValues())
        ]]);
    }
}
