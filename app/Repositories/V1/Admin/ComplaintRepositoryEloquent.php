<?php

namespace App\Repositories\V1\Admin;

use App\Criteria\V1\SearchCriteria;
use App\Repositories\V1\Base\BaseRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class RefusalRepositoryEloquent
 * @package App\Repositories\V1\Admin
 */
class ComplaintRepositoryEloquent extends BaseRepository implements ComplaintRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'reason_for_refusal.type',
        'complaintable_type',
    ];

//    /**
//     * CategoryRepositoryEloquent constructor.
//     * @param Application $app
//     */
//    public function __construct(Application $app) {
//        parent::__construct($app);
//
//        $localeName = "reason_for_refusal.refusal->" . app()->getLocale();
//
//        $this->fieldSearchable = [
//            'reason_for_refusal.type',
//            'complaintable_type',
//            $localeName
//        ];
//    }

    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot() {
        $this->pushCriteria(app(SearchCriteria::class));
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string {
        return \App\Models\Admin\Support\Complaint::class;
    }

    /**
     * @param array $ids
     * @return void
     */
    public function multipleDelete(array $ids): void {
        $this->deleteWhere([['id', 'IN', $ids]]);
    }
}
