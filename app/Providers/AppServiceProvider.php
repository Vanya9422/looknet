<?php

namespace App\Providers;

use App\Contracts\UserSettingsCodeConfirmationHandlerInterface;
use App\Services\V1\Users\UserConfirmationHandler;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        if ($this->app->isLocal()) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind(UserSettingsCodeConfirmationHandlerInterface::class, UserConfirmationHandler::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {}
}
