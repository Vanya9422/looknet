<?php

namespace App\Repositories\V1\Admin\Category;

use App\Criteria\V1\Category\WithoutChildren;
use App\Criteria\V1\SearchCriteria;
use App\Models\Admin\Categories\Filter;
use App\Models\Admin\Categories\FilterAnswer;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class FilterRepositoryEloquent
 * @package App\Repositories\V1\Admin\Category
 */
class FilterRepositoryEloquent extends BaseRepository implements FilterRepository {

    /**
     * @var array
     */
    protected $fieldSearchable = ['answer_id', 'category_id', 'with_values', 'min_value', 'max_value'];

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
        return \App\Models\Admin\Categories\Filter::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot() {
        $this->pushCriteria(app(WithoutChildren::class));
        $this->pushCriteria(app(SearchCriteria::class));
    }

    /**
     * @param array $attributes
     * @throws ValidatorException|\Throwable
     * @return mixed
     */
    public function create(array $attributes): mixed {
        DB::transaction(function () use ($attributes, &$result) {

            foreach ($attributes as $attribute) {
                $filter = parent::create($attribute);

                if ($filter instanceof Filter && isset($attribute['answers'])) {
                    $this->makeAnswers(
                        $filter,
                        $attribute['answers'],
                        $attribute['type'],
                        $attribute['category_id'] ?? null
                    );
                }

                $result[] = $filter->fresh()->load(['category', 'answers']);
            }
        });

        return $result;
    }

    /**
     * @param iterable $filters
     * @return array
     * @throws \Throwable
     */
    public function filtersUpdate(iterable $filters): array {

        DB::transaction(function () use ($filters, &$result) {
            foreach ($filters as $filter) {
                $filter['id'] = $filter['id'] ?? null;

                $newFilter = parent::updateOrCreate(['id' => $filter['id']], $filter);

                if ($newFilter instanceof Filter && isset($filter['answers'])) {
                    $function = is_null($filter['id']) ? 'makeAnswers' : 'updateAnswers';

                    $this->$function(
                        $newFilter,
                        $filter['answers'],
                        $filter['type'],
                        $filter['category_id'] ?? null
                    );
                }

                $result[] = $newFilter->fresh()->load(['category', 'answers']);
            }
        });

        return $result;
    }

    /**
     * @param FilterAnswer $answer
     * @param array $subFilters
     * @param int $type
     * @param int|null $category_id
     * @return void
     * @throws ValidatorException
     */
    private function makeFilters(
        FilterAnswer $answer,
        array $subFilters,
        int $type,
        int|null $category_id
    ) {
        foreach ($subFilters as $subFilter) {
            $subFilter['category_id'] = $category_id;
            $subFilter['type'] = $type;
            $subFilter['answer_id'] = $answer->id;
            $answers = $subFilter['answers'] ?? $subFilter['answers'] ?? null;

            $newSubFilter = parent::create($subFilter);

            if ($newSubFilter instanceof Filter && $answers) {
                $this->makeAnswers($newSubFilter, $answers, $type, $category_id);
            }
        }
    }

    /**
     * @param Filter|FilterAnswer $filter
     * @param array $answers
     * @param int $type
     * @param int|null $category_id
     * @return void
     */
    private function makeAnswers(
        Filter|FilterAnswer $filter,
        array $answers,
        int $type,
        int|null $category_id
    ) {
        $answersData = $answers;

        $answers = collect($answers)->map(function ($item, $key) use (
            $type,
            $answersData
        ) {
            if(
                $type === 1
                && ( isset($answersData[$key]['answers']) || isset($answersData[$key]['sub_filters']) )
            ) {
                $item['has_sub_filters'] = true;
            }

            return new FilterAnswer($item);
        });

        $answers = $filter->answers()->saveMany($answers);

        if ($answers instanceof \Illuminate\Support\Collection) {

            $answers->map(function ($item, $key) use ($category_id, $type, $answersData) {

                if (isset($answersData[$key]['sub_filters'])) {
                    $this->makeFilters($item, $answersData[$key]['sub_filters'], $type, $category_id);
                }

                return true;
            });
        }
    }

    /**
     * @param Filter|FilterAnswer $filter
     * @param array $answers
     * @param int $type
     * @param int|null $category_id
     * @return void
     * @throws ValidatorException
     */
    private function updateAnswers(
        Filter|FilterAnswer $filter,
        array $answers,
        int $type,
        int|null $category_id
    ) {
        $answerRepo = app(AnswerRepository::class);

        foreach ($answers as $answer) {
            $answer['filter_id'] = $filter->id;
            $answer['id'] = $answer['id'] ?? null;

            $updatedAnswer = $answerRepo->updateOrCreate(['id' => $answer['id']], $answer);

            if (is_null($answer['id']) && isset($answer['sub_filters'])) {
                $this->makeFilters($updatedAnswer, $answer['sub_filters'], $type, $category_id);
            }

            if (!is_null($answer['id']) && isset($answer['sub_filters'])) {

                foreach ($answer['sub_filters'] as $subFilter) {
                    $subFilter['id'] = $subFilter['id'] ?? null;
                    $subFilter['answer_id'] = $updatedAnswer['id'];
                    $subFilter['category_id'] = $category_id;
                    $subFilter['type'] = $type;

                    $updatedOrCreatedFilter = parent::updateOrCreate(['id' => $subFilter['id']], $subFilter);

                    if (is_null($subFilter['id']) && isset($subFilter['answers'])) {
                        $this->makeAnswers($updatedOrCreatedFilter, $subFilter['answers'], $type, $category_id);
                    }

                    if (!is_null($subFilter['id']) && isset($subFilter['answers'])) {
                        $this->updateAnswers(
                            $updatedOrCreatedFilter,
                            $subFilter['answers'],
                            $type,
                            $category_id
                        );
                    }
                }
            }
        }
    }

    /**
     * @param $filter
     * @return void
     * @throws \Throwable
     */
    public function deleteFilter($filter): void {
        \DB::transaction(function () use ($filter) {

            $this->recursiveDelete($filter);

            $filter->delete();
        });
    }

    /**
     * @param $filter
     * @return void
     */
    public function recursiveDelete($filter) {
        $filter->answers->map(function ($answer) {

            $answer->filters->map(function ($filter) {

                if ($filter->answers()->exists()) {
                    $this->recursiveDelete($filter);
                }

                $filter->delete();
            });

            $answer->delete();
        });
    }
}
