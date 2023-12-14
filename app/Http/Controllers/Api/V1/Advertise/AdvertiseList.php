<?php

namespace App\Http\Controllers\Api\V1\Advertise;

use App\Criteria\V1\Advertises\AdvertiseByCategoryWhereIn;
use App\Criteria\V1\Advertises\AdvertiseFilters;
use App\Criteria\V1\Advertises\AdvertiseFiltersValues;
use App\Criteria\V1\Advertises\DisabledStatuses;
use App\Criteria\V1\Advertises\JoinRelations;
use App\Criteria\V1\Advertises\VipProductsCriteria;
use App\Criteria\V1\Published;
use App\Criteria\V1\Payment\SubscriptionGepUpCriteria;
use App\Criteria\V1\Users\BannedUserCriteria;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AdvertiseResource;
use App\Models\Media;
use App\Models\Products\Advertise;
use App\Repositories\V1\AdvertiseRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class AdvertiseList
 */
class AdvertiseList extends Controller {

    /**
     * @param AdvertiseRepository $advertiseRepository
     */
    public function __construct(private AdvertiseRepository $advertiseRepository) {}

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection {
        // Обновляем поисковый запрос пользователя в репозитории
        $this->advertiseRepository->updateOrCreateSearchText($request->query('search'));

        // Назначаем критерии для репозитория на основе запроса
        $this->pushCriteria($request);

        // Применяем условия к запросу и получаем результат
        $query = $this->applyQueryConditions($request);

        $result = $query->with($this->getRelations())->paginate($request->query('per_page'));
        // Построение и возврат коллекции ресурсов
        return $this->buildResourceCollection($result, $request);
    }

    /**
     * метод для добавления критериев в репозиторий.
     *
     * @param Request $request
     */
    private function pushCriteria(Request $request): void {
        $this->advertiseRepository
            ->has('category')
            ->pushCriteria(JoinRelations::class)
            ->pushCriteria(DisabledStatuses::class)
            ->pushCriteria(SubscriptionGepUpCriteria::class)
            ->pushCriteria(BannedUserCriteria::class)
            ->pushCriteria(Published::class)
            ->pushCriteria(VipProductsCriteria::class);

        if ($request->query('category_id')) {
            $this->advertiseRepository->pushCriteria(AdvertiseByCategoryWhereIn::class);
        }

        if ($request->query('filters')) {
            $this->advertiseRepository->pushCriteria(AdvertiseFilters::class);
        }

        if ($request->query('filters_values')) {
            $this->advertiseRepository->pushCriteria(AdvertiseFiltersValues::class);
        }
    }

    /**
     * метод для применения условий запроса.
     *
     * @param Request $request
     * @return mixed
     */
    private function applyQueryConditions(Request $request): mixed
    {
        $search = $request->get('search');

        if ($search && !$this->hasMilAndCityId($search)) {
            $select = Advertise::$selectedFields;
            $select[] = 'ct.name as city_name';

            return $this->advertiseRepository->select($select);
        }

        return $this->advertiseRepository;
    }

    /**
     * метод для получения связей модели.
     *
     * @return array
     */
    private function getRelations(): array {
        return [
            'previewImage' => fn ($query) => $query->select(Media::$selectedFields),
        ];
    }

    /**
     * метод для построения коллекции ресурсов.
     *
     * @param $result
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    private function buildResourceCollection($result, Request $request): AnonymousResourceCollection {
        $resourceCollection = AdvertiseResource::collection($result);

        if (!$request->has('vip_products')) {
            $topAdvertises = $this->advertiseRepository->getTopAdvertisesRandomOrder();

            $resourceCollection->additional([
                'top_advertises' => AdvertiseResource::collection($topAdvertises)
            ]);
        }

        return $resourceCollection;
    }

    /**
     * Проверяет наличие 'mil' и 'city_id' в строке поиска.
     *
     * @param string $searchString
     * @return bool
     */
    private function hasMilAndCityId(string $searchString): bool {
        return preg_match('/\bcity_id:\d+\b/', $searchString) && preg_match('/\bmil:\d+/', $searchString);
    }
}
