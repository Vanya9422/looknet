<?php

namespace App\Repositories\V1;

use App\Models\Admin\Countries\City;
use App\Repositories\V1\Base\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class CityRepositoryEloquent
 * @package App\Repositories\V1
 */
class CityRepositoryEloquent extends BaseRepository implements CityRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'name' => 'LIKE',
        'order' => 'BETWEEN',
        'state_code',
        'state_id'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string {
        return \App\Models\Admin\Countries\City::class;
    }

    /**
     * Boot up the repository, pushing criteria
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot() {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param array $data
     * @return object|null
     */
    public function findCityByLatLongAndCityName(array $data): object|null {
        return City::query()
            ->with('state_minimal_select')
            ->when(isset($data['city_name']), function ($q) use ($data) {
                $formattedQuery = str_replace(' ', '', strtolower($data['city_name']));
                $q->whereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%$formattedQuery%"]);
            })
            ->when(isset($data['state_name']) || isset($data['state_code']), function ($q) use ($data) {
                $q->whereHas('state', function ($q) use ($data) {
                    $q->when(isset($data['state_name']), function ($q) use ($data) {
                        $stateName = str_replace(' ', '', strtolower($data['state_name']));
                        $q->whereRaw("REPLACE(LOWER(name), ' ', '') LIKE ?", ["%$stateName%"]);
                    });
                    $q->when(isset($data['state_code']), function ($q) use ($data) {
                        $q->where('state_code', '=', $data['state_code']);
                    });
                });
            })
            ->when(isset($data['latitude']) && isset($data['longitude']), function ($q) use ($data) {
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
                $q->selectRaw("cities.id, cities.name, cities.state_id, cities.latitude, cities.longitude, (
                    3959 * acos (
                      cos (radians($latitude))
                      * cos(radians(latitude))
                      * cos(radians(longitude) - radians($longitude))
                      + sin(radians($latitude))
                      * sin(radians(latitude))
                    )
                  ) AS distance"
                )->having('distance', '<', 5);
            })
            ->first();
    }
}
