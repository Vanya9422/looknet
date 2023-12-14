<?php

namespace App\Http\Controllers\Api\V1;

use App\Criteria\V1\Advertises\AdvertiseByCategoryWhereIn;
use App\Criteria\V1\Advertises\AdvertiseFilters;
use App\Criteria\V1\Advertises\AdvertiseFiltersValues;
use App\Criteria\V1\Advertises\JoinRelations;
use App\Criteria\V1\Payment\SubscriptionGepUpCriteria;
use App\Criteria\V1\Published;
use App\Criteria\V1\Users\BannedUserCriteria;
use App\Enums\Advertise\AdvertiseStatistic;
use App\Enums\Advertise\AdvertiseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AdvertiseRequest;
use App\Http\Requests\V1\AdvertisesRequest;
use App\Http\Resources\V1\Admin\Category\CategoryResource;
use App\Http\Resources\V1\AdvertiseResource;
use App\Http\Resources\V1\MediaResource;
use App\Models\Products\Advertise;
use App\Models\Media;
use App\Repositories\V1\Admin\Category\CategoryRepository;
use App\Repositories\V1\AdvertiseRepository;
use App\Repositories\V1\Media\MediaRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class AdvertiseController
 */
class AdvertiseController extends Controller {

    use ApiResponseAble;

    /**
     * AdvertiseController constructor.
     * @param AdvertiseRepository $advertiseRepository
     */
    public function __construct(private AdvertiseRepository $advertiseRepository) { }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getSearchTexts(Request $request): LengthAwarePaginator {
        return $this->advertiseRepository->searchTexts($request->query('search'));
    }

    /**
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return AnonymousResourceCollection
     */
    public function getCategoriesAndAdvertisesCountBySearch(
        Request $request,
        CategoryRepository $categoryRepository
    ): AnonymousResourceCollection {
        $search = $request->query('search');
        $city = +$request->query('city_id');

        [$categories, $advertises] = $categoryRepository->searchCategoriesByAdvertiseCounts(
            $search,
            $city
        );

        return CategoryResource::collection($categories)->additional(['advertises' => $advertises]);
    }

    /**
     * @param Advertise $advertise
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function reviewInfo(Advertise $advertise) {
        return !$advertise->is_fake
            ? $advertise->author->ratingReviews()
            : $advertise->author->ratingReviews($advertise->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdvertiseRequest $request
     * @return AdvertiseResource|JsonResponse
     */
    public function store(AdvertiseRequest $request): AdvertiseResource|JsonResponse {
        try {
            $this->authorize(__FUNCTION__, Advertise::class);

            $advertise = $this->advertiseRepository->create($request->all());

            return new AdvertiseResource(
                $advertise->load(['category', 'gallery', 'city', 'answers', 'fake_user_avatar'])
            );
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @param MediaRepository $mediaRepository
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function gallery(int $id, MediaRepository $mediaRepository): AnonymousResourceCollection|JsonResponse {
        try {
            $gallery = $mediaRepository
                ->findWhere(
                    [
                        ['model_id', '=', $id],
                        ['model_type', '=', Advertise::class]
                    ],
                    Media::$selectedFields
                );

            return MediaResource::collection($gallery);
        } catch (\Exception | ModelNotFoundException $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $idOrSlug
     * @return AdvertiseResource|JsonResponse
     * @throws \Throwable
     */
    public function show($idOrSlug): AdvertiseResource|JsonResponse {
        try {
            $advertise = $this->advertiseRepository
                ->with('fake_user_avatar')
                ->has('category')
                ->findWhere([
                    ['slug', '=', $idOrSlug],
                    ['author', 'HAS', function(){}],
                ])
                ->first();

            throw_if(!(bool)$advertise, new ModelNotFoundException(__('messages.ITEM_NOTFOUND')));

            return AdvertiseResource::make($advertise);
        } catch (\Exception | ModelNotFoundException $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdvertiseRequest $request
     * @param Advertise $advertise
     * @return AdvertiseResource|JsonResponse
     */
    public function update(AdvertiseRequest $request, Advertise $advertise): AdvertiseResource|JsonResponse {
        try {
            $this->authorize(__FUNCTION__, $advertise);

            $advertise = $this->advertiseRepository->updateAdvertise($request->all(), $advertise);

            return new AdvertiseResource($advertise->load(['category', 'gallery', 'city', 'answers', 'fake_user_avatar']));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * @param AdvertisesRequest $request
     * @return JsonResponse
     */
    public function addFavorite(AdvertisesRequest $request): JsonResponse {

        $advertiseIds = [];

        foreach ($request->get('advertises') as $id) {
            $advertiseIds[$id] = [
                'type' => AdvertiseStatistic::Favorite,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $request->user()->favorites()->attach($advertiseIds);
        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }

    /**
     * @param AdvertisesRequest $request
     * @return JsonResponse
     */
    public function detachFavorite(AdvertisesRequest $request): JsonResponse {
        $request->user()->favorites()->detach($request->get('advertises'));
        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }

    /**
     * @param Advertise $advertise
     * @param Media $media
     * @throws AuthorizationException
     * @return JsonResponse
     */
    public function deletePicture(Advertise $advertise, Media $media): JsonResponse {
        $this->authorize(__FUNCTION__, [$advertise, $media]);

        $media->delete();

        return $this->success('', __('messages.ITEM_DELETED'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AdvertisesRequest $request
     * @param $action
     * @throws AuthorizationException
     * @return JsonResponse
     */
    public function changeProductStatusOrDeleteProduct(AdvertisesRequest $request, $action): JsonResponse {
        $advertises = $this->advertiseRepository->findWhereIn('id', $request->get('advertises'));

        $active = AdvertiseStatus::fromValue(AdvertiseStatus::Active);
        $notverified = AdvertiseStatus::fromValue(AdvertiseStatus::NotVerified);
        $inactive = AdvertiseStatus::fromValue(AdvertiseStatus::InActive);

        foreach ($advertises as $advertise) {
            $this->authorize(__FUNCTION__, $advertise);

            if ($action === 'delete') {
                $advertise->delete();
            }

            if ($action === 'draft') {
                $advertise->update(['status' => AdvertiseStatus::Draft]);
            }

            if ($action === 'moderation') {
                $advertise->update(['status' => AdvertiseStatus::NotVerified]);
            }

            if ($action === 'deactivate' && ($active->is($advertise->status) || $notverified->is($advertise->status))) {
                $advertise->update(['status' => AdvertiseStatus::InActive]);
            }

            if ($action === 'activate' && $inactive->is($advertise->status)) {
                $advertise->update(['status' => AdvertiseStatus::Active]);
            }
        }

        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }

    /**
     * @param \App\Models\Products\Advertise $advertise
     * @return JsonResponse
     */
    public function addStatisticsPhoneView(Advertise $advertise): JsonResponse {
        $advertise->increment('show_phone');
        $advertise->save();
        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }

    /**
     * @param \App\Models\Products\Advertise $advertise
     * @return JsonResponse
     */
    public function addStatisticsDetailsView(Advertise $advertise): JsonResponse {
        $advertise->increment('show_details');
        $advertise->save();
        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }

    /**
     * @param Advertise $advertise
     * @return JsonResponse
     */
    public function addStatisticsFavorite(Advertise $advertise): JsonResponse {
        $advertise->increment('added_favorites');
        $advertise->save();
        return $this->success('', __('messages.SUCCESS_OPERATED'));
    }
}
