<?php

namespace Database\Factories\Admin\Support;

use App\Models\Admin\Support\SupportTheme;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Support\SupportTheme>
 */
class SupportThemeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupportTheme::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['title' => "string"])] public function definition(): array
    {
        return [
            'title' => $this->faker->title()
        ];
    }
}
