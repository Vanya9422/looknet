<?php

namespace App\Services\V1\Users;

use App\Enums\Users\SubscriptionStatuses;
use App\Models\Admin\Commercial\CommercialUsers;
use App\Repositories\V1\Admin\Commercial\CommercialUserRepository;
use App\Repositories\V1\Users\SubscriptionRepository;
use App\Repositories\V1\Users\UserRepository;
use App\Services\V1\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Throwable;

/**
 * Class SubscriptionService
 * @package App\Services\V1\Users
 */
class SubscriptionService extends BaseService
{

    /**
     * @var \Stripe\StripeClient
     */
    private \Stripe\StripeClient $stripeClient;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param SubscriptionRepository $subscriptionRepository
     * @param CommercialUserRepository $commercialUserRepository
     */
    public function __construct(
        private UserRepository           $userRepository,
        private SubscriptionRepository   $subscriptionRepository,
        private CommercialUserRepository $commercialUserRepository,
    )
    {
    }

    /**
     * @return SubscriptionRepository
     */
    public function getRepo(): SubscriptionRepository
    {
        return $this->subscriptionRepository;
    }

    /**
     * @return UserRepository
     */
    public function userRepo(): UserRepository
    {
        return $this->userRepository;
    }

    /**
     * @return CommercialUserRepository
     */
    public function commercialUserRepo(): CommercialUserRepository
    {
        return $this->commercialUserRepository;
    }

    /**
     * @return mixed
     */
    public function model(): Model
    {
        return $this->getRepo()->getModel();
    }

    /**
     * @param array $data
     * @return Session
     * @throws Throwable
     */
    public function checkoutUrl(array $data): Session
    {

        DB::transaction(function () use (&$checkout, $data) {

            $this->initStripApi();

            $plan = $this->commercialUserRepository->find($data['id']);

            $metaData = $this->generateMetaData($plan, $data['owner']);

            /**
             * Создаем Платеж Для Клиента в Стрип
             */
            $checkout = $this->stripeCheckoutUrlGenerate($plan, $data, $metaData);
        });

        return $checkout;
    }

    /**
     * @param array $data
     * @return void
     */
    public function addSubscriptionModerator(array $data)
    {
        $plan = $this->commercialUserRepository->find($data['id']);

        $metaData = $this->generateMetaData($plan, $data['owner']);

        $data = array_merge([
            'payload' => [],
            'status' => SubscriptionStatuses::ACTIVE,
        ], $metaData);

        if (!isset($data['auto_renewal'])) {
            $data['auto_renewal'] = true;
        }

        $this->getRepo()->create($data);
    }

    /**
     * TODO Убрать потом Отсюда и поставить в класс (Stripe)
     * @param $plan
     * @param array $data
     * @param array $metaData
     * @return Session
     * @throws ApiErrorException
     */
    private function stripeCheckoutUrlGenerate($plan, array $data, array $metaData = []): Session
    {
        return $this->stripeClient->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => $data['success_url'],
            'cancel_url' => $data['cancel_url'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'USD',
                    'unit_amount' => $plan->price * 100,
                    'product_data' => [
                        'name' => $plan->name,
                        'description' => json_decode($plan->description)[0]->text,
                        //'images' => ['https://picsum.photos/200/300'],
                    ],
                ],
                'quantity' => 1,
            ]],
            'metadata' => $metaData,
        ]);
    }

    /**
     * TODO Надо Будет поменять реализацию сейчас пока используем Апи Страипа
     */
    private function initStripApi(): void
    {
        $this->stripeClient = new \Stripe\StripeClient([
            "api_key" => config('stripe-webhooks.signing_secret'),
            "stripe_version" => "2022-08-01"
        ]);
    }

    /**
     * TODO Убрать потом Отсюда и поставить в класс (Stripe)
     *
     * @param $plan
     * @param Model $owner
     * @return array
     */
    public function generateMetaData($plan, Model $owner): array
    {
        $metaData = [
            'owner_id' => $owner->getKey(),
            'owner_type' => $owner->getMorphClass(),
            'plan_id' => $plan->getKey(),
            'plan_type' => $plan->getMorphClass()
        ];

        if ($plan instanceof CommercialUsers) {

            if ($plan->vip_days) {
                $metaData['expired_vip_days'] = \Carbon\Carbon::now()->addDays($plan->vip_days)->toDateString();
            }

            if ($plan->top_days) {
                $metaData['expired_top_days'] = \Carbon\Carbon::now()->addDays($plan->top_days)->toDateString();
            }

            if ($plan->period_days) {
                $metaData['expired_period_gep_up'] = \Carbon\Carbon::now()->addDays($plan->period_days)->toDateString();
            }
        }

        return $metaData;
    }
}
