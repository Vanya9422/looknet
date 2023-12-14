<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\Admin\Category\AnswerResource;
use App\Http\Resources\V1\Admin\Category\CategoryResource;
use App\Http\Resources\V1\Chat\ConversationsResource;
use App\Http\Resources\V1\User\FakeUserResource;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * Class AdvertiseResource
 * @package App\Http\Resources\V1\Admin\Category
 *
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed name
 * @property object category
 * @property array answers
 * @property mixed slug
 * @property mixed description
 * @property mixed price
 * @property mixed link
 * @property mixed latitude
 * @property mixed longitude
 * @property mixed contacts
 * @property mixed price_policy
 * @property mixed refusal_comment
 * @property mixed show_phone
 * @property mixed contact_phone
 * @property mixed show_details
 * @property mixed added_favorites
 * @property mixed auto_renewal
 * @property mixed status
 * @property mixed available_cost
 * @property mixed renewal
 * @property mixed address
 * @property mixed inactively_date
 * @property mixed contact_phone_numeric
 * @property mixed $exists_unread_messages
 * @property mixed $answer_ids
 * @property mixed $gep_up_id
 * @property mixed $up_date
 * @property mixed $is_vip
 * @property mixed $is_top
 * @property mixed $is_up
 * @property mixed $formatted_filters
 * @property mixed $is_fake
 * @property mixed $fake_user_avatar
 * @property mixed $fake_data
 * @property mixed $published
 * @property mixed $complaint
 * @property mixed $hide_address
 * @property mixed $previewImage
 * @property mixed $user_id
 * @property mixed $city_name
 * @property mixed $author_name
 * @property mixed $gallery
 */
class AdvertiseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($this->resource instanceof LengthAwarePaginator) return parent::toArray($request);

        if ($request instanceof Request && $request->route()->uri === 'api/products' && $this->gep_up_id) {
            \DB::table('advertise_gep_ups')->where('gep_up_id', $this->gep_up_id)->delete();
        }

        $data = [
            'id' => $this->whenNotNull($this->id),
            'name' => $this->whenNotNull($this->name),
            'description' => $this->whenNotNull($this->description),
            'slug' => $this->whenNotNull($this->slug),
            'price' => $this->whenNotNull($this->price),
            'status' => $this->whenNotNull($this->status),
            'answer_ids' => $this->whenNotNull($this->answer_ids),
            'link' => $this->whenNotNull($this->link),
            'formatted_filters' => $this->whenNotNull($this->formatted_filters),
            'contacts' => $this->whenNotNull($this->contacts),
            'city_name' => $this->whenNotNull($this->city_name),
            'published' => $this->whenNotNull($this->published),
            'contact_phone' => $this->contact_phone,
            'contact_phone_numeric' => $this->whenNotNull($this->contact_phone_numeric),
            'inactively_date' => $this->whenNotNull($this->inactively_date),
            'auto_renewal' => $this->whenNotNull($this->auto_renewal),
            'price_policy' => $this->whenNotNull($this->price_policy),
            'user_id' => $this->whenNotNull($this->user_id),
            'hide_address' => $this->whenNotNull($this->hide_address),
            'available_cost' => $this->whenNotNull($this->available_cost),
            'show_phone' => $this->whenNotNull($this->show_phone),
            'gep_up' => (bool)$this->up_date,
            $this->mergeWhen($this->addressInformationClosed($request), [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'address' => $this->address
            ]),
            'show_details' => $this->whenNotNull($this->show_details),
            'added_favorites' => $this->whenNotNull($this->added_favorites),
            'fake_data' => $this->whenNotNull($this->fake_data),
            'created_at' => $this->whenNotNull($this->created_at),
            'updated_at' => $this->whenNotNull($this->updated_at),
            'refusal_comment' => $this->whenLoaded('complaint', function () {
                return $this->complaint->description;
            }),
            'city' => CityResource::make($this->whenLoaded('city')),
            'conversation' => ConversationsResource::make($this->whenLoaded('conversation')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'answers' => AnswerResource::collection($this->whenLoaded('answers'))
        ];

        if ($request->has('is_top')) {
            $data['is_top'] = $this->is_top;
        }

        if (array_key_exists('gallery', $this->getRelations())) {
            $data['gallery'] = MediaResource::collection($this->gallery);
        }

        if (array_key_exists('fake_user_avatar', $this->getRelations())) {
            $data['fake_image'] = MediaResource::make($this->fake_user_avatar);
        }

        if (array_key_exists('previewImage', $this->getRelations()) && $this->previewImage) {
            $data['gallery'] =  MediaResource::collection([$this->previewImage]);
        }

        if ($this->is_fake) {
            $data['is_fake'] = $this->is_fake;
            $data['author'] = FakeUserResource::make(
                $this->whenLoaded('author'),
                $this->id
            );
        }

        if (!$this->is_fake) {
            $data['author'] = UserResource::make($this->whenLoaded('author'));
        }

        if ($request->has('main')) {
            $data['is_up'] = $this->is_up;
            $data['is_vip'] = $this->is_vip;
            $data['is_top'] = $this->is_top;
            $data['exists_unread_messages'] = $this->whenNotNull($this->exists_unread_messages);
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function addressInformationClosed(Request $request): bool {
        if ($request->user() && $request->user()->id === $this->user_id) return true;

        return !$this->hide_address;
    }
}
