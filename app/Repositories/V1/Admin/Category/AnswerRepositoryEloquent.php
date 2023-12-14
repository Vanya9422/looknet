<?php

namespace App\Repositories\V1\Admin\Category;

use App\Criteria\V1\SearchCriteria;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class AnswerRepositoryEloquent
 * @package App\Repositories\V1\Admin\Category
 */
class AnswerRepositoryEloquent extends BaseRepository implements AnswerRepository {

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name' => 'LIKE',
        'number_value',
        'string_value',
        'boolean_value',
        'has_sub_filters',
        'filter_id',
    ];

    /**
     * CategoryRepositoryEloquent constructor.
     * @param Application $app
     */
    public function __construct(Application $app) {
        parent::__construct($app);

        $localeName = "name->" . app()->getLocale();

        $this->fieldSearchable = array_merge([$localeName], $this->fieldSearchable);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string {
        return \App\Models\Admin\Categories\FilterAnswer::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot() {
        $this->pushCriteria(app(SearchCriteria::class));
    }

    /**
     * @param $answer
     * @return void
     * @throws \Throwable
     */
    public function deleteAnswer($answer): void {
        \DB::transaction(function () use ($answer) {
            $filterRepo = app(FilterRepositoryEloquent::class);

            $answer->filters->map(function ($filter) use ($filterRepo) {
                $filterRepo->recursiveDelete($filter);

                $filter->delete();
            });

            $answer->delete();
        });
    }
}
