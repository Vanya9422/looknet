<?php

namespace App\Http\Requests\V1\Review;

use App\Http\Requests\V1\FormRequest;
use App\Models\Review;
use App\Traits\StripTagsAble;

/**
 * Class ReviewRequest
 * @package App\Http\Requests\V1\Reviews
 */
class ReviewRequest extends FormRequest {

    use StripTagsAble;

    private array $striping_columns = ['comment'];

    /**
     * @return bool
     */
    public function authorize(): bool {

        if ($this->getMethod() === 'PUT') {
            $review = Review::findOrFail($this->get('id'));

            $this->request->set('review', $review);
        }

        return true;
    }

    /**
     *|---------------------------------------------------------------------------
     *| Validation Rules for Validatable trait
     *|---------------------------------------------------------------------------
     *| @var array[] $rules
     */
    protected array $rules = [
        'POST' => [
            'pictures' => 'nullable|array|max:10',
            'pictures.*.' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'comment' => 'required|string|max:2000|min:10',
            'star' => 'required|numeric|between:1,5',
            'status' => 'required|numeric|between:0,3',
            'advertise_id' => 'required|exists:advertises,id',
        ],
        'PUT' => [
            'id' => 'required|exists:reviews,id',
            'pictures' => 'nullable|array|max:10',
            'pictures.*.' => 'nullable|mimetypes:image/jpeg,image/heic,image/bmp,image/jpg,image/png,image/gif,image/webp|max:10240', // 10 mb
            'comment' => 'nullable|string|max:2000|min:10',
            'star' => 'nullable|numeric|between:1,5',
            'status' => 'nullable|numeric|between:0,3',
        ],
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return $this->rules[$this->getMethod()];
    }

    /**
     * @return Review
     */
    public function review(): Review {
        return $this->get('review');
    }
}
