<?php

namespace App\Http\Resources\V1\Admin\Category;

use App\Http\Resources\V1\User\RoleResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * Class AnswerResource
 * @package App\Http\Resources\V1\Admin\Category
 *
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed name
 * @property object answer
 * @property mixed order
 * @property mixed $value
 * @property number $number_value
 * @property string $string_value
 * @property mixed $has_sub_filters
 * @property mixed $boolean_value
 * @property mixed $filters
 * @property mixed $sub_filters
 * @property mixed $filter
 */
class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable {
        if ($this->resource instanceof LengthAwarePaginator) {
            return parent::toArray($request);
        }

        return [
            'id' => $this->whenNotNull($this->id),
            'name' => $this->whenNotNull($this->name),
            'order' => $this->whenNotNull($this->order),
            'string_value' => $this->whenNotNull($this->string_value),
            'number_value' => $this->whenNotNull($this->number_value),
            'boolean_value' => $this->whenNotNull($this->boolean_value),
            'has_sub_filters' => $this->whenNotNull($this->has_sub_filters),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            $this->mergeWhen($this->whenLoaded('filter'), [
                'filter' => FilterResource::make($this->whenLoaded('filter')),
            ]),
            $this->mergeWhen($this->whenLoaded('sub_filters'), [
                'sub_filters' => FilterResource::collection($this->whenLoaded('sub_filters')),
            ]),
            $this->mergeWhen($this->whenLoaded('filters'), [
                'sub_filters' => FilterResource::collection($this->whenLoaded('filters')),
            ]),
        ];
    }
}
