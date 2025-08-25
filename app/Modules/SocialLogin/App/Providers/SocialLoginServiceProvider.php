<?php

namespace Modules\SocialLogin\App\Providers;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\ServiceProvider;
use Modules\SocialLogin\App\Application\Commands\SocialUser\CreateSocialUserCommand;
use Modules\SocialLogin\App\Application\Commands\SocialUser\UpdateSocialUserCommand;
use Modules\SocialLogin\App\Application\Handlers\SocialUser\HandleCreateOrUpdateSocialUser;
use Modules\SocialLogin\App\Application\Handlers\User\HandleGetSocialUserByUserIdAndProvider;
use Modules\SocialLogin\App\Application\Handlers\User\HandleGetUserBySocialLogin;
use Modules\SocialLogin\App\Application\Queries\SocialUser\GetSocialUserByUserIdAndProviderQuery;
use Modules\SocialLogin\App\Application\Queries\User\GetUserBySocialLoginQuery as UserGetUserBySocialLoginQuery;

class SocialLoginServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'SocialLogin';

    protected string $moduleNameLower = 'sociallogin';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
        
        Bus::map([
            UserGetUserBySocialLoginQuery::class => HandleGetUserBySocialLogin::class,
            GetSocialUserByUserIdAndProviderQuery::class => HandleGetSocialUserByUserIdAndProvider::class,
            
            CreateSocialUserCommand::class => HandleCreateOrUpdateSocialUser::class,
            UpdateSocialUserCommand::class => HandleCreateOrUpdateSocialUser::class,
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }
}
