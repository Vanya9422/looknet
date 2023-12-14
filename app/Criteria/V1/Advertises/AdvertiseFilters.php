<?php

namespace App\Criteria\V1\Advertises;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SearchCriteria
 * @package App\Criteria\V1
 */
class AdvertiseFilters implements CriteriaInterface
{
    protected Request $request;

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
        $filters = json_decode($this->request->query('filters'), true);

        $filterKeys = array_keys($filters);

        foreach ($filterKeys as $filterKey) $this->getQueryFiltersWithRelations($model, $filters[$filterKey]);

        return $model;
    }

    /**
     * @param $query
     * @param $filters
     * @param int|null $countQueries
     * @return void
     */
    private function getQueryFilters(&$query, $filters, int &$countQueries = null) {
        if (!$countQueries) $countQueries = 0;

        $whereOrWhere = 'where';

        if ($countQueries > 1) {
            $whereOrWhere = count($filters) === 1 ? 'where' : 'orWhere';
        }

        ++$countQueries;

        foreach ($filters as $key => $filter) {
            $query->{$whereOrWhere} (function ($q) use ($key, $filter, $countQueries) {

                if (!is_array($filter) && $key === $filter) {
                    $q->where('answer_ids', 'LIKE', "%[$key]%");
                }

                if (!is_array($filter) && $key !== $filter) {
                    $q->where('answer_ids', 'LIKE', "%[$key][$filter]%");
                }

                if (is_array($filter)) {
                    $this->getQueryFilters($q, $filter, $countQueries);
                }
            });
        }
    }

    /**
     * @param $query
     * @param $filters
     * @return void
     */
    private function getQueryFiltersWithRelations(&$query, $filters) {

        $query->where(function ($q) use ($filters) {
            $condition = count($filters) > 1 ? 'orWhereHas' : 'whereHas';

            foreach ($filters as $key => $filter) {

                if (!is_array($filter) && $key === $filter) {
                    $q->{$condition}('answers', function($q) use ($key) {
                        $q->whereId($key);
                    });
                }

                if (!is_array($filter) && $key !== $filter) {
                    $q->{$condition}('answers', function($q) use ($key) {
                        $q->whereId($key);
                    });
                    $q->{$condition}('answers', function($q) use ($filter) {
                        $q->whereId($filter);
                    });
                }

                if (is_array($filter)) {
                    $this->getQueryFiltersWithRelations($q, $filter);
                }
            }
        });
    }
}
