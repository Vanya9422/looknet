<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Review\ReviewPublishedRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Repositories\V1\ReviewRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     */
    public function list(Request $request): AnonymousResourceCollection {
        $selectUser = "id,first_name,last_name,banned,phone,email,created_at";

        return ReviewResource::collection(
            $this->repository->with([
                "user:$selectUser",
                "author:$selectUser",
                'pictures',
                'author.avatar',
                'user.avatar',
                'advertise.previewImage',
                'advertise:id,name'
            ])
            ->paginate($request->query('per_page'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewPublishedRequest $request
     * @return JsonResponse
     */
    public function published(ReviewPublishedRequest $request): JsonResponse {
        try {
            $review = $request->review();

            $review->update([
                'published' => $request->get('published'),
                'published_at' => now(),
            ]);

            return $this->success([], __('messages.SUCCESS_OPERATED'));
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
