<?php

namespace App\Http\Requests\V1\Auth;

use App\Http\Requests\V1\FormRequest;
use App\Models\Admin\Support\Notification;
use App\Traits\FormRequestAuthorizeAble;

/**
 * Class LoginRequest
 * @package App\Http\Requests\V1\Auth
 */
class LoginRequest extends FormRequest {

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
        return $this->rulesEmail[$this->get('confirmation_type')];
    }
}
