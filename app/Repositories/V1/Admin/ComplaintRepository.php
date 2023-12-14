<?php

namespace App\Repositories\V1\Admin;

use App\Repositories\V1\Base\BaseContract;

/**
 * Interface ComplaintRepository
 * @package App\Repositories\V1\Admin
 */
interface ComplaintRepository extends BaseContract
{
    /**
     * @param array $ids
     */
    public function multipleDelete(array $ids): void;
}
