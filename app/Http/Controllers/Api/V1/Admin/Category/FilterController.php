<?php

namespace App\Http\Controllers\Api\V1\Admin\Category;

use App\Criteria\V1\Category\FiltersByCategoryWhereIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Category\FilterRequest;
use App\Http\Resources\V1\Admin\Category\AnswerResource;
use App\Http\Resources\V1\Admin\Category\FilterResource;
use App\Models\Admin\Categories\Filter;
use App\Models\Admin\Categories\FilterAnswer;
use App\Repositories\V1\Admin\Category\AnswerRepository;
use App\Repositories\V1\Admin\Category\FilterRepository;
use App\Services\V1\Admin\CategoryService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class FilterController
 * @package App\Http\Controllers\Api\V1\Admin\Category
 */
class FilterController extends Controller {

    use ApiResponseAble;

    /**
     * FilterController constructor.
     * @param FilterRepository $filterRepository
     * @param CategoryService $categoryService
     */
    public function __construct(
        private FilterRepository $filterRepository,
        private CategoryService $categoryService,
    ) { }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Admin\Categories\Filter $filter
     * @return FilterResource
     */
    public function show(Filter $filter): FilterResource {
        return new FilterResource(
            $filter->load(['category', 'answersWithoutSubFilters'])
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function list(Request $request): AnonymousResourceCollection {
        if ($request->query('category_id', false)) {
            $this->filterRepository->pushCriteria(FiltersByCategoryWhereIn::class);
        }

        return FilterResource::collection(
            $this->filterRepository->paginate($request->query('per_page'))
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param AnswerRepository $answerRepository
     * @return AnonymousResourceCollection
     */
    public function answersList(Request $request, AnswerRepository $answerRepository): AnonymousResourceCollection {
        return AnswerResource::collection(
            $answerRepository->noValues()->paginate($request->query('per_page'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FilterRequest $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function store(FilterRequest $request): AnonymousResourceCollection|JsonResponse {
        try {
            return FilterResource::collection(
                $this->filterRepository->create($request->all())
            );
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FilterRequest $request
     * @return AnonymousResourceCollection|JsonResponse
     * @throws \Throwable
     */
    public function update(FilterRequest $request): AnonymousResourceCollection|JsonResponse {
        try {
            $filters = $this->filterRepository->filtersUpdate($request->all());

            return FilterResource::collection($filters);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource
     *
     * @param Filter $filter
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(Filter $filter): JsonResponse {
        $this->filterRepository->deleteFilter($filter);

        return $this->success('', __('messages.ITEM_DELETED'));
    }

    /**
     * @param FilterAnswer $filterAnswer
     * @return JsonResponse
     * @throws \Throwable
     */
    public function answerDelete(FilterAnswer $filterAnswer): JsonResponse {
        app(AnswerRepository::class)->deleteAnswer($filterAnswer);

        return $this->success('', __('messages.ITEM_DELETED'));
    }
}
