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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_id')->unique()->nullable();
            $table->boolean('status');
            $table->boolean('auto_renewal')->default(false);
            $table->text('payload');
            $table->morphs('plan');
            $table->morphs('owner');
            $table->timestamp('expired_period_gep_up')->nullable();
            $table->timestamp('expired_vip_days')->nullable();
            $table->timestamp('expired_top_days')->nullable();
            $table->timestamp('cancelled_at')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
};
