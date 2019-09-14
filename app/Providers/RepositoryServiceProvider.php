<?php

namespace Emrad\Providers;

use Illuminate\Support\ServiceProvider;
use Emrad\Repositories\RoleRepository;
use Emrad\Repositories\ImageRepository;
use Emrad\Repositories\PermissionRepository;
use Emrad\Repositories\Contracts\RoleRepositoryInterface;
use Emrad\Repositories\Contracts\ImageRepositoryInterface;
use Emrad\Repositories\Contracts\PermissionRepositoryInterface;

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
