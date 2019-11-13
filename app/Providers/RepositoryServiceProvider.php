<?php

namespace Emrad\Providers;

use Emrad\Services\UsersServices;
use Emrad\Services\CompaniesServices;
use Emrad\Repositories\RoleRepository;
use Emrad\Repositories\UserRepository;
use Emrad\Repositories\ImageRepository;
use Illuminate\Support\ServiceProvider;
use Emrad\Repositories\CompanyRepository;
use Emrad\Repositories\CategoryRepository;
use Emrad\Repositories\PermissionRepository;
use Emrad\Repositories\Contracts\RoleRepositoryInterface;
use Emrad\Repositories\Contracts\UserRepositoryInterface;
use Emrad\Repositories\Contracts\ImageRepositoryInterface;
use Emrad\Repositories\Contracts\CompanyRepositoryInterface;
use Emrad\Repositories\Contracts\CategoryRepositoryInterface;
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
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind('fc-users-repo-interface', function ($app){ return $app->make(UserRepositoryInterface::class); });
        $this->app->bind('fc-users-services', function ($app){ return $app->make(UsersServices::class); });
        $this->app->bind('fc-company-repo-interface', function ($app){ return $app->make(CompanyRepositoryInterface::class); });
        $this->app->bind('fc-company-services', function ($app){ return $app->make(CompaniesServices::class); });
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
