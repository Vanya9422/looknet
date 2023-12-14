<?php

namespace App\Http\Resources\V1;

use App\Enums\Reviews\ReviewStatusEnum;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StateResource
 * @package App\Http\Resources\V1
 * @property int $id
 * @property string $comment
 * @property int $star
 * @property boolean $published
 * @property int $status
 * @property mixed $has_image
 * @property mixed $created_at
 * @property mixed $published_at
 */
class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request): array|\JsonSerializable|Arrayable {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'star' => $this->star,
            'status' => $this->status,
            'translated_status' => ReviewStatusEnum::getDescription($this->status),
            'published' => $this->published,
            'has_image' => $this->has_image,
            'created_at' => $this->created_at,
            'published_at' => $this->published_at,
            'author' => UserResource::make($this->whenLoaded('author')),
            'user' => UserResource::make($this->whenLoaded('user')),
            'product' => AdvertiseResource::make($this->whenLoaded('advertise')),
            'pictures' => MediaResource::collection($this->whenLoaded('pictures')),
        ];
    }
}
