<?php

namespace App\Http\Requests\V1\Admin;

use App\Http\Requests\V1\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Class RefusalRequest
 * @package App\Http\Requests\V1\Admin
 */
class ComplaintsIdsRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array {
        return [
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:complaints,id'
        ];
    }
}
