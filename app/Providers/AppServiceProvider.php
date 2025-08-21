<?php

namespace App\Providers;

use App\Application\Commands\User\CreateUserCommand;
use App\Application\Handlers\User\HandleCreateUser;
use App\Application\Handlers\User\HandleGetUserById;
use App\Application\Queries\User\GetUserByIdQuery;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
        ]);
        
        Bus::map([
            CreateUserCommand::class => HandleCreateUser::class,
            GetUserByIdQuery::class => HandleGetUserById::class,
        ]);

    }
}
