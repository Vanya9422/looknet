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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->integer('star')->index();
            $table->tinyInteger('status')
                ->default(\App\Enums\Reviews\ReviewStatusEnum::Default)
                ->index()
                ->comment('Информация о status детально описано в файле ReviewStatusEnum');

            $table->tinyInteger('published')
                ->default(\App\Enums\Reviews\ReviewPublishedEnum::NewReviewed)
                ->index()
                ->comment('Информация о published детально описано в файле ReviewPublishedEnum');

            $table->boolean('has_image')->default(false);
            $table->timestamp('published_at')->nullable()->default(null);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete(); // ид того кто написал отзив
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // ид того кому написали отзив
            $table->foreignId('advertise_id')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('reviews');
    }
};
