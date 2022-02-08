<?php

namespace Emrad\Providers;

use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\Repositories\WalletRepository;
use Emrad\Services\RolesServices;
use Emrad\Services\UsersServices;
use Emrad\Services\CompaniesServices;
use Emrad\Repositories\RoleRepository;
use Emrad\Repositories\SaleRepository;
use Emrad\Repositories\UserRepository;
use Emrad\Repositories\ImageRepository;
use Emrad\Repositories\OfferRepository;
use Emrad\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;
use Emrad\Repositories\CompanyRepository;
use Emrad\Repositories\ProductRepository;
use Emrad\Repositories\CategoryRepository;
use Emrad\Repositories\InventoryRepository;
use Emrad\Repositories\PermissionRepository;
use Emrad\Repositories\Contracts\RoleRepositoryInterface;
use Emrad\Repositories\Contracts\SaleRepositoryInterface;
use Emrad\Repositories\Contracts\UserRepositoryInterface;
use Emrad\Repositories\Contracts\ImageRepositoryInterface;
use Emrad\Repositories\Contracts\OfferRepositoryInterface;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Repositories\Contracts\CompanyRepositoryInterface;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;
use Emrad\Repositories\Contracts\CategoryRepositoryInterface;
use Emrad\Repositories\Contracts\InventoryRepositoryInterface;
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
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
        $this->app->bind('fc-users-repo-interface', function ($app){ return $app->make(UserRepositoryInterface::class); });
        $this->app->bind('fc-users-services', function ($app){ return $app->make(UsersServices::class); });
        $this->app->bind('fc-company-repo-interface', function ($app){ return $app->make(CompanyRepositoryInterface::class); });
        $this->app->bind('fc-company-services', function ($app){ return $app->make(CompaniesServices::class); });
        $this->app->bind('fc-role-services', function ($app){ return $app->make(RolesServices::class); });
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
