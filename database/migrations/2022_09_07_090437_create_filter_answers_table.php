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
    public function up() {
        Schema::create('filter_answers', function (Blueprint $table) {
            $table->id();
            $table->string('name',300)->nullable();
            $table->string('string_value')->nullable();
            $table->float('number_value', 11)->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->boolean('has_sub_filters')->default(0);
            $table->integer('order')->default(0);
            $table->foreignId('filter_id')->nullable()->constrained()
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
        Schema::dropIfExists('filter_answers');
    }
};
