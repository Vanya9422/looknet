<?php

namespace Database\Factories\Admin\Categories;

use App\Models\Admin\Categories\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function fake;

/**
 * Class CategoryFactory
 * @package Database\Factories
 */
class CategoryFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['name' => "string"])] public function definition(): array {

        \DB::table('languages')
            ->get()
            ->collect()
            ->map(function ($item) use (&$name) {
                $name[$item->code] = fake($item->regional)->name();
            });

        return ['name' => $name];
    }
}
