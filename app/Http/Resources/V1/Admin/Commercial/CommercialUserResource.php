<?php

namespace App\Http\Resources\V1\Admin\Commercial;

use App\Http\Resources\V1\MediaResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * Class CommercialUserResource
 * @package App\Http\Resources\V1\Admin\Category
 *
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed name
 * @property mixed type
 * @property mixed status
 * @property mixed content
 * @property mixed locale
 * @property mixed description
 * @property mixed price
 * @property mixed gep_up
 * @property mixed order
 * @property mixed vip_days
 * @property mixed top_days
 * @property mixed period_days
 * @property mixed $group_id
 */
class CommercialUserResource extends JsonResource
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
            'description' => $this->whenNotNull($this->description),
            'status' => $this->whenNotNull($this->status),
            'price' => $this->whenNotNull($this->price),
            'group_id' => $this->whenNotNull($this->group_id),
            'top_days' => $this->whenNotNull($this->top_days),
            'vip_days' => $this->whenNotNull($this->vip_days),
            'gep_up' => $this->whenNotNull($this->gep_up),
            'period_days' => $this->whenNotNull($this->period_days),
            'order' => $this->whenNotNull($this->order),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'avatar' => MediaResource::make($this->whenLoaded('avatar')),
        ];
    }
}
