<?php

namespace App\Repositories\V1;

use App\Models\Review;
use App\Models\User;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ReviewRepository
 * @package App\Repositories\V1
 */
interface ReviewRepository extends RepositoryInterface {

    /**
     * @param array $attributes
     * @return \App\Models\Review
     */
    public function addReview(array $attributes): \App\Models\Review;

    /**
     * @param array $attributes
     * @return \App\Models\Review
     */
    public function updateReview(array $attributes): \App\Models\Review;

    /**
     * @param User $user
     * @param Review $review
     * @param array $attributes
     * @return void
     */
    public function addComplaint(User $user, Review $review, array $attributes): void;
}
