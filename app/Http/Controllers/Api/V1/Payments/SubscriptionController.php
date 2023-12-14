<?php

namespace App\Http\Controllers\Api\V1\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Users\CheckoutUrlRequest;
use App\Services\V1\Users\SubscriptionService;
use App\Traits\ApiResponseAble;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Api\V1\Users
 */
class SubscriptionController extends Controller {

    use ApiResponseAble;

    /**
     * AuthController constructor.
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(private SubscriptionService $subscriptionService) { }

    /**
     * @param CheckoutUrlRequest $request
     * @return JsonResponse
     *@throws \Throwable
     */
    public function createSession(CheckoutUrlRequest $request): JsonResponse {
        try {
            if (
                ($request->user()->isModerator() && $request->user()->hasPermissionTo('special_advertise_account')) ||
                $request->user()->isAdmin()
            ) {
                $this->subscriptionService->addSubscriptionModerator($request->all());
            } else {
                $checkout_url = $this->subscriptionService->checkoutUrl($request->all());
            }

            return $this->success(['checkout_url' => $checkout_url->url ?? '']);
        } catch (ApiErrorException | \Exception $e) {
            Log::error($e);
            return $this->clientErrorResponse($e->getMessage());
        }
    }
}
