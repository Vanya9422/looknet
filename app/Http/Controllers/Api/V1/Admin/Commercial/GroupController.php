<?php

namespace App\Http\Controllers\Api\V1\Admin\Commercial;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Commercial\GroupRequest;
use App\Http\Resources\V1\Admin\Commercial\GroupResource;
use App\Models\Group;
use App\Repositories\V1\Admin\Commercial\GroupRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class GroupController
 * @package App\Http\Controllers\Api\V1\Admin\Commercial
 */
class GroupController extends Controller {

    use ApiResponseAble;

    /**
     * GroupController constructor.
     * @param GroupRepository $groupRepository
     */
    public function __construct(private GroupRepository $groupRepository) { }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function list(Request $request): AnonymousResourceCollection {
        return GroupResource::collection(
            $this->groupRepository->paginate($request->query('per_page'))
        );
    }

    /**
     * @param GroupRequest $request
     * @return GroupResource|JsonResponse
     */
    public function updateOrCreate(GroupRequest $request): GroupResource|JsonResponse {
        try {
            return new GroupResource(
                $this->groupRepository->updateOrCreateModel($request->all())
            );
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Admin\Categories\\App\Models\Group $periodOfStay
       * @return JsonResponse|GroupResource
     */
    public function details(Group $periodOfStay) : JsonResponse|GroupResource{
        return new GroupResource($periodOfStay);
    }
    /**
     * @param \App\Models\Admin\Categories\\App\Models\Group $periodOfStay
     * @return JsonResponse
     */
    public function destroy(Group $group): JsonResponse {
        try {
            $group->delete();

            return $this->success([], __('messages.ITEM_DELETED'));
        } catch (\Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
