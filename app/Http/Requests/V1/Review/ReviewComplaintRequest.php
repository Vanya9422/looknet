<?php

namespace App\Http\Requests\V1\Review;

use App\Http\Requests\V1\FormRequest;
use App\Models\Review;

/**
 * Class ReviewComplaintRequest
 * @package App\Http\Requests\V1\Chat
 */
class ReviewComplaintRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        $review = Review::findOrFail($this->get('id'));

        $this->request->set('review', $review);

        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array {
        return [
            'id' => 'required|exists:reviews',
            'files' => 'nullable|array|max:3',
            'files.*' => 'required|file|max:2560', // 2.5 mb
            'refusal_id' => 'nullable|exists:refusals,id',
            'description' => 'required|string|max:1000',
        ];
    }

    /**
     * @return Review
     */
    public function review(): Review {
        return $this->get('review');
    }
}
