<?php

namespace App\Http\Requests\V1\Review;

use App\Http\Requests\V1\FormRequest;
use App\Models\Media;
use App\Models\Review;
use App\Traits\StripTagsAble;

/**
 * Class ReviewPictureDeleteRequest
 * @package App\Http\Requests\V1\Reviews
 */
class ReviewPictureDeleteRequest extends FormRequest {

    /**
     * @return bool
     */
    public function authorize(): bool {

        $picture = Media::findOrFail($this->get('picture_id'));

        $this->request->set('picture', $picture);

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array {
        return ['picture_id' => 'required|numeric'];
    }

    /**
     * @return Media
     */
    public function picture(): Media {
        return $this->get('picture');
    }
}
