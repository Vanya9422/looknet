<?php

namespace Database\Factories\Admin\Categories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;
use function fake;

/**
 * Class FilterAnswerFactory
 * @package Database\Factories
 */
class FilterAnswerFactory extends Factory
{
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
