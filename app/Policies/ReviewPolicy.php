<?php

namespace App\Policies;

use App\Models\Products\Advertise;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ReviewPolicy {

    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param Advertise $advertise
     * @return Response
     */
    public function store(User $user, Advertise $advertise): Response {

        return $user->id === $advertise->user_id
            ? Response::deny('Ви не можете писать отзыв своим товарам.')
            : Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Review $review
     * @return Response
     */
    public function update(User $user, Review $review): Response {

        return $user->id === $review->author_id
            ? Response::allow()
            : Response::deny('Ви не можете писать отзыв своим товарам.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Review $review
     * @return bool
     */
    public function addComplaint(User $user, Review $review): bool {
        return $user->id === $review->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Review $review
     * @return bool
     */
    public function destroyPicture(User $user, Review $review): bool {
        return $user->id === $review->author_id;
    }
}
