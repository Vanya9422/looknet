<?php

namespace App\Observers;

use App\Models\Payment\Subscription;
use App\Models\Products\Advertise;
use App\Notifications\V1\AdvertiseSubscriptionSuccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class SubscriptionObserver
 * @package App\Observers
 */
class SubscriptionObserver {

    /**
     * Handle the Advertise "updated" event.
     *
     * @param Subscription $subscription
     * @return void
     */
    public function created(Subscription $subscription) {
        $gepUp = $subscription?->planable?->gep_up;
        $period_days = $subscription?->planable?->period_days;

        if ($subscription->ownerable instanceof Advertise) {
            $advertise = $subscription->ownerable;
            $advertise->author->notify(new AdvertiseSubscriptionSuccess($subscription, $advertise));
        }

        if ($gepUp && $period_days) {
            $ups = 0;

            if ($period_days < $gepUp) {
                $period_days = ($gepUp - ($gepUp - $period_days));
            }

            for ($i = 0; $i < $gepUp; $i++) {
                $ups += (int)round($period_days / $gepUp);

                DB::table('advertise_gep_ups')->insert([
                    'up_date' => Carbon::now()->addDays($ups),
                    'advertise_id' => $subscription->owner_id,
                ]);
            }
        }
    }
}
