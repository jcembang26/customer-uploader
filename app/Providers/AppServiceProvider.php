<?php

namespace App\Providers;

use App\Interfaces\CustomerInterface;
use App\Services\CustomerService;
use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\Connection;
use App\Services\DoctrineConnectionFactory;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Doctrine DBAL connection
        $this->app->singleton(Connection::class, function ($app) {
            return DoctrineConnectionFactory::make();
        });
        $this->app->bind(CustomerInterface::class, CustomerService::class);
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
