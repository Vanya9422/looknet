<?php

namespace App\Http\Requests\V1\Review;

use App\Http\Requests\V1\FormRequest;
use App\Models\Media;
use App\Models\Review;
use App\Traits\StripTagsAble;

/**
 * Class ReviewPublishedRequest
 * @package App\Http\Requests\V1\Reviews
 */
class ReviewPublishedRequest extends FormRequest {

    /**
     * @return bool
     */
    public function authorize(): bool {

        $review = Review::findOrFail($this->get('id'));

        $this->request->set('review', $review);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return [
            'id' => 'required|numeric',
            'published' => 'required|numeric|between:1,2',
            'refusal_id' => 'nullable|exists:refusals,id',
            'refusal_comment' => 'nullable|string|max:1000'
        ];
    }

    /**
     * @return Review
     */
    public function review(): Review {
        return $this->get('review');
    }
}
