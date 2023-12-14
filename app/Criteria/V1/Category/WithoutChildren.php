<?php

namespace App\Criteria\V1\Category;

use App\Services\V1\Admin\CategoryService;
use App\Traits\ParserAble;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class WithoutChildren
 * @package App\Criteria\V1\Advertises
 */
class WithoutChildren implements CriteriaInterface
{
    use ParserAble;

    protected Request $request;
    protected CategoryService $categoryService;

    /**
     * SearchCriteria constructor.
     */
    public function __construct()
    {
        $this->request = app(Request::class);
        $this->categoryService = app(CategoryService::class);
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
        $searchConfig = config('repository.criteria.params.search', 'search');
        $searchData = $this->parserSearchData($this->request->get($searchConfig));
        $enableQueries = !$this->request->has('category_id');
        $withoutFilterChildren = !isset($searchData['answer_id']);
        $withoutCategoryChildren = isset($searchData['category_id']);
        return $model->when($enableQueries, function ($q) use (
            $withoutFilterChildren, $withoutCategoryChildren
        ) {
            $q->when($withoutFilterChildren && !$withoutCategoryChildren, function ($q) {
                $q->whereNull('answer_id');
            })->when(!$withoutFilterChildren && $withoutCategoryChildren, function ($q) {
                $q->whereNull('category_id');
            });
        });
    }
}
