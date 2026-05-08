<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\{UserRepository, LeaveRepository};
use App\Repositories\Interfaces\{UserRepositoryInterface, LeaveRepositoryInterface};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            LeaveRepositoryInterface::class,
            LeaveRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
