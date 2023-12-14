<?php

namespace App\Criteria\V1\Payment;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SubscriptionGepUpCriteria
 * @package App\Criteria\V1\Users
 */
class SubscriptionGepUpCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model
            ->leftJoin('advertise_gep_ups as up', function($join) {
                $join->on('advertises.id', '=', 'up.advertise_id');
                $join->whereDate('up.up_date', '=', Carbon::today()->toDateString());
            })
            ->orderBy(\DB::raw('ISNULL(up_date), up_date'), 'ASC')
            ->orderBy('advertises.created_at', 'asc');
    }
}
