<?php

namespace App\Http\Resources\V1\Chat;

use App\Http\Resources\V1\ComplaintAbleResource;
use App\Http\Resources\V1\MediaResource;
use App\Http\Resources\V1\RefusalResource;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources\V1\User
 *
 * @property mixed id
 * @property mixed email
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed description
 * @property mixed files
 * @property mixed refusal
 * @property mixed user
 * @property mixed $user_full_name
 */
class ComplaintResource extends JsonResource {

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
            'user_full_name' => $this->user_full_name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => UserResource::make($this->whenLoaded('user')),
            'reason_for_refusal' => RefusalResource::make($this->whenLoaded('reason_for_refusal')),
            'model' => ComplaintAbleResource::make($this->whenLoaded('complaintable')),
            'files' => MediaResource::collection($this->whenLoaded('files')),
        ];
    }
}
