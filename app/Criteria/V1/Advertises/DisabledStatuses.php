<?php

namespace App\Criteria\V1\Advertises;

use App\Enums\Advertise\AdvertiseStatus;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class JoinRelations
 */
class DisabledStatuses implements CriteriaInterface {

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository) {
        return $model->whereIn('advertises.status', AdvertiseStatus::getValues(['Active', 'NotVerified']));
    }
}
