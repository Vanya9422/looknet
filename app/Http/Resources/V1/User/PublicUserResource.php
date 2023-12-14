<?php

namespace App\Http\Resources\V1\User;

use App\Http\Resources\V1\AdvertiseResource;
use App\Http\Resources\V1\MediaResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources\V1\User
 *
 * @property mixed id
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed full_name
 * @property mixed email
 * @property mixed phone
 * @property object avatar
 * @property string phone_view
 * @property mixed advertises_count
 * @property mixed canceled_advertises_count
 * @property mixed $created_at
 * @property mixed $rating_reviews
 * @property mixed $phone_code
 */
class PublicUserResource extends JsonResource {

    /**
     * @var bool
     */
    public static $wrap = false;

    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable {

        if ($this->resource instanceof LengthAwarePaginator) {
            return parent::toArray($request);
        }

        return [
            'id' => $this->whenNotNull($this->id),
            'first_name' => $this->whenNotNull($this->first_name),
            'last_name' => $this->whenNotNull($this->last_name),
            'full_name' => $this->whenNotNull($this->full_name),
            'phone_code' => $this->whenNotNull($this->phone_code),
            'phone' => (bool)$this->phone,
            'email' => (bool)$this->email,
            'phone_view' => $this->whenNotNull($this->phone_view),
            'advertises_count' => $this->whenNotNull($this->advertises_count),
            'rating_reviews' => $this->ratingReviews(),
            'canceled_advertises_count' => $this->whenNotNull($this->canceled_advertises_count),
            'registered' => $this->created_at,
            'created_at' => $this->created_at,
            'advertises' => AdvertiseResource::collection($this->whenLoaded('advertises')),
            'avatar' => $this->whenLoaded('avatar', function () {
                return MediaResource::make($this->avatar);
            }),
        ];
    }
}
