<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiSuccessResponseAble
{
    /**
     * Return a success JSON response.
     *
     * @param null $data
     * @param string|null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function success($data = null, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
        ], $code);
    }
}
