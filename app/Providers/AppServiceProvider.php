<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use App\Models\PersonalAccessToken;
use App\Repositories\V1\FilterFactory;
use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\Helpers\QueryHelper;
use App\Repositories\V1\Helpers\FilterOptimizer;
use App\Repositories\V1\Helpers\RepositoryParams;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Repository V1 facades.
         */
        $this->app->bind('repository-params', RepositoryParams::class);
        $this->app->bind('repository-filter-factory', FilterFactory::class);
        $this->app->bind('repository-filter-optimizer', FilterOptimizer::class);
        $this->app->bind('repository-query-helper', QueryHelper::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
