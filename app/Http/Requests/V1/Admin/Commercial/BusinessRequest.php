<?php

namespace App\Http\Requests\V1\Admin\Commercial;

use App\Http\Requests\V1\FormRequest;
use App\Traits\StripTagsAble;

/**
 * Class BusinessRequest
 * @package App\Http\Requests\V1\Users
 */
class BusinessRequest extends FormRequest {

    use StripTagsAble;

    private array $striping_columns = ['details'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'id' => 'nullable|exists:commercial_businesses',
            'files' => 'nullable|array|max:10',
            'files.*.media_id' => 'required|exists:media,id',
            'client_id' => 'required|exists:clients,id',
            'name' => 'nullable|string|max:100|min:2',
            'type' => 'required|numeric|between:0,1',
            'link' => 'required|string|max:100|min:2',
            'location' => 'nullable|string|max:100|min:2',
            'details' => 'nullable|string',
            'status' => 'required|numeric|between:0,2',
        ];
    }
}
