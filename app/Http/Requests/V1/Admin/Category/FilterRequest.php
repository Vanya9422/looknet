<?php

namespace App\Http\Requests\V1\Admin\Category;

use App\Enums\Admin\Filters\FilterTypesEnum;
use App\Http\Requests\V1\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class FilterRequest
 * @package App\Http\Requests\V1\Admin\Category
 */
class FilterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // min_value max_value number_value
        // Эти правила позволят значениям min_value и max_value быть либо целыми числами,
        // либо числами с плавающей запятой с двумя десятичными знаками. Регулярное выражение /^\d+(?:\.\d{1,2})?$/
        // позволяет одному или более цифрам до точки (если она присутствует), за которыми могут следовать до двух
        // цифр после точки.

        return [
            '*.id' => 'nullable|exists:filters',
            '*.name' => 'nullable|string|max:50',  // translatable
            '*.min_name' => 'nullable|string|max:500', // translatable
            '*.max_name' => 'nullable|string|max:500',  // translatable
            '*.sub_filter_names' => 'nullable|string',  // translatable
            '*.type' => 'required|numeric|' . Rule::in(FilterTypesEnum::getValues()),
            '*.order' => 'nullable|numeric',
            '*.min_value' => 'nullable|regex:/^\d+(?:\.\d{1,2})?$/',
            '*.max_value' => 'nullable|regex:/^\d+(?:\.\d{1,2})?$/',
            '*.category_id' => 'nullable|exists:categories,id',
            '*.answers.*.id' => 'nullable|exists:filter_answers',
            '*.answers.*.name' => 'nullable|string',
            '*.answers.*.number_value' => 'nullable|regex:/^\d+(?:\.\d{1,2})?$/',
            '*.answers.*.string_value' => 'nullable|string',
            '*.answers.*.boolean_value' => 'nullable|boolean',
            '*.answers.*.order' => 'nullable|numeric|numeric',
            '*.answers.*.sub_filters' => 'nullable|array',
            '*.answers.*.sub_filters.*.id' => 'nullable|exists:filters',
            '*.answers.*.sub_filters.*.name' => 'nullable|string|max:50',
            '*.answers.*.sub_filters.*.order' => 'nullable|numeric',
            '*.answers.*.sub_filters.*.answers.*.id' => 'nullable|exists:filter_answers',
            '*.answers.*.sub_filters.*.answers.*.name' => 'nullable|string',
            '*.answers.*.sub_filters.*.answers.*.number_value' => 'nullable|regex:/^\d+(?:\.\d{1,2})?$/',
            '*.answers.*.sub_filters.*.answers.*.string_value' => 'nullable|string',
            '*.answers.*.sub_filters.*.answers.*.order' => 'nullable|numeric|numeric'
        ];
    }
}
