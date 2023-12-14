<?php

namespace App\Http\Requests\V1\Admin\Page;

use App\Enums\Page\PageTypes;
use App\Http\Requests\V1\FormRequest;
use App\Traits\StripTagsAble;
use Illuminate\Validation\Rule;

/**
 * Class PageRequest
 * @package App\Http\Requests\V1
 */
class PageRequest extends FormRequest {

    use StripTagsAble;

    private array $striping_columns = ['content'];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        $page_id = $this->get('id');
        $rules = [
            'id' => 'nullable|exists:pages,id',
            'files' => 'nullable|array|max:10',
            'files.*.media_id' => 'required|exists:media,id',
            'background_images.*' => 'mimes:jpeg,jpg,png,svg,heic,bmp,gif,webp|max:5120', // 5 mb
            'name' => 'nullable|string|max:255|min:2',
            'locale' => 'required|string|max:5|min:1',
            'page_key' => 'required|string|unique:pages,page_key',
            'type' => 'string|required|' . Rule::in(PageTypes::getValues()),
            'content' => 'string|required',
        ];

        if ($page_id) {
            $rules['page_key'] = "required|string|unique:pages,page_key,$page_id";
        }

       return $rules;
    }
}
