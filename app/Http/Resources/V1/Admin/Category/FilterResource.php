<?php

namespace App\Http\Resources\V1\Admin\Category;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * Class FilterResource
 * @package App\Http\Resources\V1\Admin\Category
 *
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed name
 * @property object category
 * @property array answers
 * @property string slug
 * @property int order
 * @property int $answer_id
 * @property int $category_id
 * @property mixed $type
 * @property mixed $with_values
 * @property ?string $min_name
 * @property ?string $max_name
 * @property ?float $max_value
 * @property ?float $min_value
 * @property mixed $sub_filter_names
 */
class FilterResource extends JsonResource
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
            'sub_filter_names' => $this->whenNotNull($this->sub_filter_names),
            'slug' => $this->whenNotNull($this->slug),
            'type' => $this->whenNotNull($this->type),
            'min_name' => $this->whenNotNull($this->min_name),
            'max_name' => $this->whenNotNull($this->max_name),
            'with_values' => $this->whenNotNull($this->with_values),
            'min_value' => $this->whenNotNull($this->min_value),
            'max_value' => $this->whenNotNull($this->max_value),
            'answer_id' => $this->whenNotNull($this->answer_id),
            'category_id' => $this->whenNotNull($this->category_id),
            'order' => $this->whenNotNull($this->order),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            $this->mergeWhen($this->whenLoaded('answers'), [
                'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            ]),
            $this->mergeWhen($this->whenLoaded('answersWithoutSubFilters'), [
                'answers' => AnswerResource::collection($this->whenLoaded('answersWithoutSubFilters')),
            ]),
            $this->mergeWhen($this->whenLoaded('answersWithSubFilters'), [
                'answers' => AnswerResource::collection($this->whenLoaded('answersWithSubFilters')),
            ])
        ];
    }
}
