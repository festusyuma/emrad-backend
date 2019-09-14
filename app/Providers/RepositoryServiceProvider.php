<?php

namespace FlexiCreative\Providers;

use Illuminate\Support\ServiceProvider;
use FlexiCreative\Repositories\RoleRepository;
use FlexiCreative\Repositories\ImageRepository;
use FlexiCreative\Repositories\PermissionRepository;
use FlexiCreative\Repositories\Contracts\RoleRepositoryInterface;
use FlexiCreative\Repositories\Contracts\ImageRepositoryInterface;
use FlexiCreative\Repositories\Contracts\PermissionRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
