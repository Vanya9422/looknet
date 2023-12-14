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
        Schema::create('refusals', function (Blueprint $table) {
            $table->id();
            $table->text('refusal');
            $table->tinyInteger('type')
                ->comment('Информация о status детально описано в файле RefusalTypeEnum.php');

            $table->integer('order');

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
        Schema::dropIfExists('refusals');
    }
};
