<?php

namespace App\Providers;

use App\Contracts\RepositoryContract;
use App\Repositories\Github;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Repository;
use Madewithlove\IlluminatePsrCacheBridge\Laravel\CacheItemPool;
use Psr\Cache\CacheItemPoolInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Performing an instance-to-interface bind for the sake of switching repository source with ease.
         * Now, anywhere we'll ask laravel to retrieve RepositoryContract from service container, we'll
         * receive a class implementing that interface.
         */
        $this->app->bind(RepositoryContract::class, function () {
            $cachePool = $this->app->make(CacheItemPoolInterface::class);

            return new Github($cachePool);
        });

        /**
         * Cache pool will be optionally used to stash the responses locally.
         */
        $this->app->singleton(CacheItemPoolInterface::class, function ($app) {
            $repository = $app->make(Repository::class);

            return new CacheItemPool($repository);
        });
    }
}
