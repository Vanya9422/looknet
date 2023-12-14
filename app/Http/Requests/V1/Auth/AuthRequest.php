<?php

namespace App\Http\Requests\V1\Auth;

use App\Http\Requests\V1\FormRequest;
use App\Models\Admin\Support\Notification;
use App\Traits\FormRequestAuthorizeAble;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class AuthRequest
 * @package App\Http\Requests\V1\Users
 */
class AuthRequest extends FormRequest {

    use FormRequestAuthorizeAble;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {

        $passwordRule = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols()
            ->uncompromised(3);

        $rule = [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone_code' => 'nullable|string',
            'phone_view' => 'nullable|string',
            'policy' => 'required|boolean',
            'password' => ['required', 'string', $passwordRule],
        ];

        if ($this->get('confirmation_type') === Notification::SMS_CONFIRMATION) {
            $rule[$this->get('field')] = [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9+]*$/',
                Rule::unique('users', 'phone')->where(function ($query) {
                    return $query->whereNotNull('verified_at');
                }),
            ];
        }

        if ($this->get('confirmation_type') === Notification::Email_CONFIRMATION) {
            $rule[$this->get('field')] = [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->whereNotNull('verified_at');
                }),
            ];
        }

        return $rule;
    }

    /**
     * @return array
     */
    #[ArrayShape(['password' => "mixed"])] public function attributes(): array {
        return [
            'password' => __('validation.password.attributes.password'),
        ];
    }
}
