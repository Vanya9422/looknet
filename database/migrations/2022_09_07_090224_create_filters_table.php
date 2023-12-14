<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->string('name',300);
            $table->boolean('with_values')->default(false);
            $table->string('slug')->unique()->nullable();
            $table->text('sub_filter_names')->nullable();
            $table->json('min_name')->nullable();
            $table->json('max_name')->nullable();
            $table->float('max_value', 11)->nullable();
            $table->float('min_value', 11)->nullable();

            $table->tinyInteger('type')
                ->default(0)
                ->comment('Информация о типе детально описано в файле FilterTypesEnum');

            $table->integer('order')->default(0);
            $table->unsignedBigInteger('answer_id')->nullable()->index();

            $table->foreignId('category_id')->nullable()->constrained()
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
};
