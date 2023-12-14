<?php

namespace App\Criteria\V1\Advertises;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class JoinRelations
 */
class JoinRelations implements CriteriaInterface {

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository) {
        return $model->leftJoin('cities as ct', 'advertises.city_id', '=', 'ct.id');
    }
}
