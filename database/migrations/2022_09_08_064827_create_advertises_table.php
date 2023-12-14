<?php

use App\Enums\Advertise\AdvertiseContacts;
use App\Enums\Advertise\AdvertiseStatus;
use App\Enums\Advertise\AdvertiseType;
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
        Schema::create('advertises', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->unique()->nullable();
            $table->string('answer_ids')->index()->nullable();
            $table->text('description');
            $table->text('formatted_filters')->nullable();
            $table->decimal('price', 10)->nullable()->index();
            $table->string('latitude');
            $table->string('longitude');
            $table->string('address');
            $table->string('link')->nullable();
            $table->boolean('hide_address')->default(false);
            $table->boolean('auto_renewal')->default(false);
            $table->boolean('published')->default(false);
            $table->string('type')
                ->index()
                ->default(AdvertiseType::REGULAR)
                ->comment('в AdvertiseType файле описанно детально');

            $table->tinyInteger('status')
                ->default(AdvertiseStatus::NotVerified)
                ->comment('Не проверено 0,  Активно 1, Не активно 2, Отклонено 3, Забанено 4, 5 Черновик');

            $table->tinyInteger('price_policy')
                ->nullable()
                ->comment('Ценовая Политика объявление. бесплатное 0,  платное 1, обмен 3');

            $table->boolean('contacts')
                ->default(AdvertiseContacts::ALL)
                ->comment('Телефон И Сообщение 0, Телефон 1, Сообщение 2');

            $table->string('contact_phone')->nullable();
            $table->bigInteger('contact_phone_numeric')->nullable();

            $table->boolean('available_cost')->default(true);
            $table->boolean('is_fake')->default(false);
            $table->integer('show_phone')->default(0);
            $table->integer('show_details')->default(0);
            $table->integer('added_favorites')->default(0);

            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();

            $table->index(['latitude', 'longitude']);
            $table->dateTime('inactively_date');
            $table->softDeletes();
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
        Schema::dropIfExists('advertises');
    }
};
