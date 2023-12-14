<?php

namespace Database\Seeders;

use App\Models\Admin\Categories\Category;
use App\Models\Admin\Countries\City;
use App\Models\Products\Advertise;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Class UserSeeder
 * @package Database\Seeders
 */
class UserSeeder extends Seeder
{

    /**
     * @var int
     */
    public static int $advertisesMaxCount = 10;

    /**
     * @var int
     */
    public static int $usersCount = 25;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected string $model = User::class;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i <= self::$usersCount; $i++) {

            $cityCount = City::count();
            $categoryCount = Category::count();

            if (!$cityCount || !$categoryCount) {
                $this->command->error('City Or Category Not Found');
            } else {
                $city = City::find(rand(1, $cityCount));
                $category = Category::find(rand(1, $categoryCount));

                User::factory()
                    ->has(
                        Advertise::factory()
                            ->for($city)
                            ->for($category)
                            ->count(rand(0, self::$advertisesMaxCount)),
                    )
                    ->create();
            }
        }
    }
}
