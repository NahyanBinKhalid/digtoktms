<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\BaseRepository;
use App\Repositories\TranslationRepository;
use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\BaseInterface;
use App\Repositories\Interfaces\TranslationInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(BaseInterface::class, BaseRepository::class);
        $this->app->bind(TranslationInterface::class, TranslationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
