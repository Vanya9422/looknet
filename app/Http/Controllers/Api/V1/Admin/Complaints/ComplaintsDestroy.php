<?php

namespace App\Http\Controllers\Api\V1\Admin\Complaints;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\ComplaintsIdsRequest;
use App\Repositories\V1\Admin\ComplaintRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ComplaintsDestroy
 * @package App\Http\Controllers\Api\V1\Admin\Complaints
 */
class ComplaintsDestroy extends Controller {

    use ApiResponseAble;

    /**
     * @param ComplaintRepository $repository
     */
    public function __construct(private ComplaintRepository $repository) {}

    /**
     * Handle the incoming request.
     *
     * @param ComplaintsIdsRequest $request
     * @return JsonResponse
     */
    public function __invoke(ComplaintsIdsRequest $request): JsonResponse
    {
        try {
            $this->repository->multipleDelete($request->get('ids', []));

            return $this->success('', __('messages.ITEM_DELETED'));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
