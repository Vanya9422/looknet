<?php

namespace App\Observers;

use App\Enums\Advertise\AdvertiseStatus;
use App\Models\Products\Advertise;
use Carbon\Carbon;

/**
 * Class AdvertiseObserver
 * @package App\Observers
 */
class AdvertiseObserver
{
    /**
     * Handle the Advertise "created" event.
     *
     * @param Advertise $advertise
     * @return void
     */
    public function created(Advertise $advertise)
    {
        $this->sendCreatedNotification($advertise);
    }

    /**
     * Handle the Advertise "updated" event.
     *
     * @param Advertise $advertise
     * @return void
     */
    public function updated(Advertise $advertise)
    {
        $this->eventForStatusUpdated($advertise);
    }

    /**
     * Handle the Advertise "deleted" event.
     *
     * @param Advertise $advertise
     * @return void
     */
    public function deleted(Advertise $advertise) {}

    /**
     * Handle the Advertise "restored" event.
     *
     * @param Advertise $advertise
     * @return void
     */
    public function restored(Advertise $advertise) {}

    /**
     * Handle the Advertise "force deleted" event.
     *
     * @param Advertise $advertise
     * @return void
     */
    public function forceDeleted(Advertise $advertise) {}

    /**
     * @param Advertise $advertise
     */
    protected function sendCreatedNotification(Advertise $advertise): void
    {
        $advertise->author->notify(new \App\Notifications\V1\Advertise([
            'title' => $advertise->name,
            'event' => __FUNCTION__,
            'message' => $advertise->description
        ]));
    }

    /**
     * @param Advertise $advertise
     */
    protected function eventForStatusUpdated(Advertise $advertise): void
    {

        if ($advertise->isDirty('status')) {

            $status = +$advertise->status;

            /**
             * Статус подтверждённого публикации (1)
             */
            $active = AdvertiseStatus::fromValue(AdvertiseStatus::Active);

            if ($active->is($status)) {

                /**
                 * Отправляем уведомление, что публикация подтверждено
                 */
                $advertise->author->notify(new \App\Notifications\V1\Advertise(
                    [
                        'title' => $advertise->name,
                        'event' => __FUNCTION__,
                        'subject' => __('mails.PublicationSuccessTitle'),
                        'message' => __('mails.PublicationSuccessText')
                    ],
                    __('mails.PublicationSuccessTitle'),
                    __('mails.PublicationSuccessText'),
                    $advertise->load('previewImage'),
                    app()->getLocale()
                ));

                /**
                 * Продлеваем срок обновления до 30 дней
                 */
                \DB::table('advertises')->where('id', '=', $advertise->id)->update([
                    'inactively_date' => Carbon::now()->addDays(30)
                ]);
            }

            /**
             * Статус отклонённого обновления (3)
             */
            $rejected = AdvertiseStatus::fromValue(AdvertiseStatus::Rejected);

            if ($rejected->is($status)) {
                $subject = $advertise?->complaint?->reason_for_refusal?->refusal ?? __('mails.PublicationCancelledTitle');
                $message = $advertise?->complaint?->description ?? __('mails.PublicationCancelledText');

                /**
                 * Отправляем уведомление, что публикация отклонено
                 */
                $advertise->author->notify(new \App\Notifications\V1\Advertise(
                    [
                        'title' => $advertise->name,
                        'subject' => $subject,
                        'event' => __FUNCTION__,
                        'message' => $message
                    ],
                    __('mails.PublicationCancelledTitle'),
                    __('mails.PublicationCancelledText'),
                    $advertise->load('previewImage'),
                    app()->getLocale()
                ));
            }
        }
    }
}
