<?php

namespace App\Criteria\V1\Advertises;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AdvertiseFiltersValues
 * @package App\Criteria\V1\Advertises
 */
class AdvertiseFiltersValues implements CriteriaInterface
{
    protected Request $request;

    protected array $acceptedConditionsForStringValues = [
        '=',
        'LIKE',
    ];

    protected array $acceptedConditionsForNumberValues = [
        '=',
        '>',
        '<',
        '>=',
        '<=',
        'between'
    ];

    /**
     * SearchCriteria constructor.
     */
    public function __construct()
    {
        $this->request = app(Request::class);
    }

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
        $filterValues = json_decode($this->request->query('filters_values'), true);

        if (is_array($filterValues)) {

            foreach ($filterValues as $filter_id => $filterValue) {

                $model = $model->whereHas('answers', function ($q) use ($filter_id, $filterValue) {

                    $q->where('filter_id', '=', $filter_id);

                    foreach ($filterValue as $item) {

                        if (isset($item['n']) && $this->existsConditionForNumberValues($item)) {
                            $value = $item['n'];
                            $condition = $item['c'];

                            if ($condition === 'between') {
                                $q->whereBetween('number_value', explode(',', $value));
                            } else {
                                $q->where('number_value', $condition, $value);
                            }
                        }

                        if (isset($item['s']) && $this->existsConditionForStringValues($item)) {
                            $value = $item['n'];
                            $condition = $item['c'];

                            if ($condition === 'LIKE') {
                                $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                                $q->where(function($q) use ($condition, $value) {
                                    $q->where('string_value', $condition, "$value%")
                                        ->orWhere('string_value', $condition, mb_convert_case($value, MB_CASE_TITLE, "UTF-8") . '%')
                                        ->orWhere('string_value', $condition, mb_strtolower($value) . '%');
                                });
                            } else {
                                $q->where('string_value', $condition, $value);
                            }
                        }
                    }
                });
            }
        }

        return $model;
    }

    /**
     * @param $item
     * @return bool
     */
    public function existsConditionForNumberValues($item): bool
    {
        return isset($item['c']) && in_array($item['c'], $this->acceptedConditionsForNumberValues);
    }

    /**
     * @param $item
     * @return bool
     */
    public function existsConditionForStringValues($item): bool
    {
        return isset($item['c']) && in_array($item['c'], $this->acceptedConditionsForStringValues);
    }
}
