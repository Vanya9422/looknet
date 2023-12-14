<?php

namespace App\Traits;

/**
 * Trait StripTagsAble
 * @package App\Traits
 */
trait StripTagsAble {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        if (property_exists($this, 'striping_columns'))
            foreach ($this->striping_columns as $item)
                $this->request->set($item, strip_tags($this->get($item)));

        return true;
    }
}
