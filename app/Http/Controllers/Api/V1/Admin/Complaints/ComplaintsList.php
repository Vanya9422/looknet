<?php

namespace App\Http\Controllers\Api\V1\Admin\Complaints;

use App\Enums\MediaCollections;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Gallery\AddFilesRequest;
use App\Http\Requests\V1\Admin\Gallery\ChangeFilePropertiesRequest;
use App\Http\Requests\V1\Admin\Gallery\MediaFilesRequest;
use App\Http\Resources\V1\Chat\ComplaintResource;
use App\Http\Resources\V1\MediaResource;
use App\Models\Media;
use App\Repositories\V1\Admin\ComplaintRepository;
use App\Repositories\V1\Media\MediaRepository;
use App\Traits\ApiResponseAble;
use App\Traits\ApiSuccessResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

/**
 * Class ComplaintsList
 * @package App\Http\Controllers\Api\V1\Admin\Complaints
 */
class ComplaintsList extends Controller {

    use ApiSuccessResponseAble;

    /**
     * @param ComplaintRepository $repository
     */
    public function __construct(private ComplaintRepository $repository) {}

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection {
        $complaints = $this->repository
            ->leftJoin('users', 'users.id', '=', 'complaints.user_id')
            ->select(['complaints.*', \DB::raw("CONCAT(users.first_name, ' ', users.last_name) as user_full_name")])
            ->paginate($request->get('per_page'));

        return ComplaintResource::collection($complaints);
    }
}
