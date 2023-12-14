<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Request;

/**
 * Class AboutMeController
 * @package App\Http\Controllers\Api\V1\Users
 */
class AboutMeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return UserResource
     */
    public function __invoke(Request $request): UserResource {
        return new UserResource(
            $request
                ->user()
                ->loadCount('unread_notifications')
                ->load([
                    'avatar',
                    'city.country:id,name,phone_code,currency_name,currency_symbol',
                    'city.state:id,name',
                ])
        );
    }
}
