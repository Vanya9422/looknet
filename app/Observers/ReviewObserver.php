<?php

namespace App\Observers;

use App\Enums\Reviews\ReviewPublishedEnum;
use App\Models\Review;
use App\Notifications\V1\Advertise;
use App\Notifications\V1\ReviewNotification;

/**
 * Class ReviewObserver
 * @package App\Observers
 */
class ReviewObserver {

    /**
     * Handle the Advertise "updated" event.
     *
     * @param Review $review
     * @return void
     */
    public function updated(Review $review) {

        $this->eventForStatusUpdated($review);
    }

    /**
     * @param Review $review
     */
    protected function eventForStatusUpdated(Review $review): void
    {
        if ($review->isDirty('published') && $review->published == ReviewPublishedEnum::Success) {

            $review->user->notify(new Advertise([
                'title' => __('messages.advertise.ADVERTISE_REVIEWED'),
                'subject' => __('messages.advertise.ADDED_NEW_REVIEW'),
                'event' => __FUNCTION__,
                'message' => $review->comment
            ]));

            $review->author->notify(new Advertise([
                'title' => __('messages.advertise.YOUR_REVIEW_APPROVED'),
                'subject' => __('messages.advertise.YOUR_REVIEW_APPROVED'),
                'event' => __FUNCTION__,
                'message' => $review->comment,
            ]));

        }

        if ($review->isDirty('published') && $review->published == ReviewPublishedEnum::Reject) {
            $review->author->notify(new Advertise([
                'title' => __('messages.advertise.REVIEW_REJECTED'),
                'subject' => __('messages.advertise.REVIEW_REJECTED'),
                'event' => __FUNCTION__,
                'message' => $review->comment,
            ]));
        }
    }
}
