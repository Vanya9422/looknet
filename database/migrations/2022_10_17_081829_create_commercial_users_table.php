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
        Schema::create('commercial_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 400);
            $table->text('description');
            $table->float('price');
            $table->tinyInteger('status')->comment('Draft 0, Active 1, Closed 2');
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->integer('vip_days')->nullable();
            $table->integer('top_days')->nullable();
            $table->integer('gep_up')->nullable();
            $table->integer('period_days')->nullable();
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('commercial_users');
    }
};
