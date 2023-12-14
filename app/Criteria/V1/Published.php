<?php

namespace App\Criteria\V1;

use App\Enums\Reviews\ReviewPublishedEnum;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class Published
 * @package App\Criteria\V1\Advertises
 */
class Published implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository) {
        return $model->where('published', '=', ReviewPublishedEnum::Success);
    }
}
