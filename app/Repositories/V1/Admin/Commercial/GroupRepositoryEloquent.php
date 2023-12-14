<?php

namespace App\Repositories\V1\Admin\Commercial;

use App\Repositories\V1\Base\BaseRepository;

/**
 * Class GroupRepositoryEloquent
 * @package App\Repositories\V1\Admin\Commercial
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'status',
        'title',
        'email',
        'description',
        'group',
    ];

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot() {}

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string {
        return \App\Models\Group::class;
    }
}
