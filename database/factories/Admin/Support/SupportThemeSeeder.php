<?php

namespace Database\Factories\Admin\Support;

use App\Models\Admin\Support\SupportTheme;
use Illuminate\Database\Seeder;

class SupportThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SupportTheme::factory()->count(rand(0, 10))->create();
    }
}
