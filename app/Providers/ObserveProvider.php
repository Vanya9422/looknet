<?php

namespace App\Providers;

use App\Models\Payment\Subscription;
use App\Models\Products\Advertise;
use App\Models\Chat\Conversation;
use App\Models\Review;
use App\Models\User;
use App\Observers\AdvertiseObserver;
use App\Observers\ConversationObserver;
use App\Observers\ReviewObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

/**
 * Class ObserveProvider
 * @package App\Providers
 */
class ObserveProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Advertise::observe(AdvertiseObserver::class);
        Conversation::observe(ConversationObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        Review::observe(ReviewObserver::class);
    }
}
