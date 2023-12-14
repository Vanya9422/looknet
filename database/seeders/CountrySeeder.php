<?php

namespace Database\Seeders;

use App\Models\Admin\Countries\City;
use App\Models\Admin\Countries\Country;
use App\Models\Admin\Countries\State;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CountrySeeder
 * @package Database\Seeders
 */
class CountrySeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @throws FileNotFoundException|\Throwable
     * @return void
     */
    public function run() {
        try {
            $countryStateCities = json_decode(\File::get(public_path('country_state_city.json')), true);

            DB::transaction(function () use ($countryStateCities) {

                foreach ($countryStateCities as $country) {

                    if ($country['id'] == 233) {
                        /*Make Countries*/
                        $countryModel = Country::create([
                            'name' => $country['name'],
                            'phone_code' => $country['phone_code'],
                            'currency' => $country['currency'],
                            'currency_name' => $country['currency_name'],
                            'currency_symbol' => $country['currency_symbol'],
                            'region' => $country['region'],
                            'subregion' => $country['subregion'],
                        ]);

                        /*Make States*/
                        if (!empty($country['states'])) {
                            foreach ($country['states'] as $state) {

                                $stateModel = State::create([
                                    'name' => $state['name'],
                                    'state_code' => $state['state_code'],
                                    'latitude' => $state['latitude'],
                                    'longitude' => $state['longitude'],
                                    'country_id' => $countryModel->id,
                                ]);

                                /*Make Cities*/
                                if (!empty($state['cities'])) {
                                    foreach ($state['cities'] as $city) {
                                        City::create([
                                            'name' => $city['name'],
                                            'state_code' => $state['state_code'],
                                            'latitude' => $city['latitude'],
                                            'longitude' => $city['longitude'],
                                            'state_id' => $stateModel->id,
                                            'country_id' => $countryModel->id,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            });

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
