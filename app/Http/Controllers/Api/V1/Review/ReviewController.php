<?php

namespace App\Http\Controllers\Api\V1\Review;

use App\Criteria\V1\Published;
use App\Enums\Advertise\AdvertiseStatus;
use App\Enums\Reviews\ReviewStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Chat\ComplaintRequest;
use App\Http\Requests\V1\Review\ReviewPictureDeleteRequest;
use App\Http\Requests\V1\Review\ReviewRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Models\Products\Advertise;
use App\Models\Review;
use App\Repositories\V1\ReviewRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Repository\Exceptions\RepositoryException;

class ReviewController extends Controller
{
    use ApiResponseAble;

    /**
     * @param ReviewRepository $repository
     */
    public function __construct(private ReviewRepository $repository) {}

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws RepositoryException
     */
    public function list(Request $request): AnonymousResourceCollection {
        $this->repository->pushCriteria(Published::class);

        $selectUser = "id,first_name,last_name,banned,phone,email,created_at";

        return ReviewResource::collection(
            $this->repository->with([
                'user:' . $selectUser,
                'pictures',
                'author.avatar',
                'advertise:id,name',
                'author:' . $selectUser
            ])
            ->orderByRaw("FIELD(status, 1, 2, 0)")
            ->paginate($request->query('per_page'))
        );
    }

    /**
     * @return JsonResponse
     */
    public function options(): JsonResponse {
        return $this->success(
            (object)ReviewStatusEnum::asSelectArray(),
            'Ключи массива это статусы отзывов'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function store(ReviewRequest $request): JsonResponse {

        try {
            $advertise = Advertise::find($request->get('advertise_id'));

            $this->authorize(__FUNCTION__, [Review::class, $advertise]);

            $reviewData = array_merge(
                $request->all(), [
                    'author_id' => user()->id,
                    'user_id' => $advertise->user_id
                ]
            );

            $this->repository->addReview($reviewData);

            return $this->success([], __('messages.SUCCESS_OPERATED'));
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewRequest $request
     * @return JsonResponse
     */
    public function update(ReviewRequest $request): JsonResponse {

        try {
            $this->authorize(__FUNCTION__, $request->review());

            $data = array_merge($request->all(), ['published' => 0]);

            $this->repository->updateReview($data);

            return $this->success([], __('messages.SUCCESS_OPERATED'));
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     *
     * @param ReviewPictureDeleteRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function destroyPicture(ReviewPictureDeleteRequest $request, Review $review): JsonResponse {
        try {
            $this->authorize(__FUNCTION__, $review);

            $request->picture()->delete();

            if (!$review->pictures()->exists()) $review->update(['has_image' => false]);

            return $this->success('', __('messages.ITEM_DELETED'));
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * @param ComplaintRequest $request
     * @param Review $review
     * @return JsonResponse
     */
    public function addComplaint(ComplaintRequest $request, Review $review): JsonResponse
    {
        try {
            $this->authorize(__FUNCTION__, $review);

            $this->repository->addComplaint($request->user(), $review, $request->all());

            return $this->success('', __('messages.SUCCESS_OPERATED'));
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
