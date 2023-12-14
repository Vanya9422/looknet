<?php

namespace App\Http\Requests\V1\Admin\Category;

use App\Http\Requests\V1\FormRequest;

/**
 * Class CategoryOrderUpdateRequest
 * @package App\Http\Requests\V1\Admin\Category
 */
class CategoryOrderUpdateRequest extends FormRequest {

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
        return [
            'orders' => 'required|array',
            'orders.*.id' => 'exists:categories,id',
            'orders.*.order' => 'required|numeric'
        ];
    }
}
