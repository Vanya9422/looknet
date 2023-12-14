<?php

namespace App\Policies;

use App\Models\Products\Advertise;
use App\Models\Media;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdvertisePolicy
 * @package App\Policies
 */
class AdvertisePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store(User $user): bool {
        if (request()->get('fake_data')
            && (
                $user->isModerator() &&
                !$user->hasPermissionTo('special_advertise_account') ||
                $user->isUser()
            )
        ) return false;

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Products\Advertise $advertise
     * @return bool
     */
    public function update(User $user, Advertise $advertise): bool {
       return $user->id === $advertise->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Advertise $advertise
     * @return bool
     */
    public function changeProductStatusOrDeleteProduct(User $user, Advertise $advertise): bool {
        return $user->id === $advertise->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param Advertise $advertise
     * @param Media $media
     * @return bool
     */
    public function deletePicture(User $user, Advertise $advertise, Media $media): bool {
        return $user->id === $advertise->user_id && $media->model_id = $advertise->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Products\Advertise $advertise
     * @return bool
     */
    public function changeStatus(User $user, Advertise $advertise): bool {
        return $user->hasRole(config('roles.roles.admin.name'))
            || $user->hasRole(config('roles.roles.moderator.name'));
    }

    /**
     * @param User $user
     * @return bool|null
     */
    public function before(User $user): bool|null
    {
        return $user->isAdmin() ? true : null;
    }
}
