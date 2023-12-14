<?php

namespace App\Criteria\V1\Advertises;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class VipProductsCriteria
 */
class VipProductsCriteria implements CriteriaInterface {

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository) {
        if (\request()->has('vip_products')) {
            return $model->whereHas('subscription', function ($q) {
                $q->where('expired_vip_days', '>=', Carbon::now());
            })->inRandomOrder();
        }

        return $model;
    }
}
