<?php

namespace Modules\ChatAI\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     */
    protected string $moduleNamespace = 'Modules\ChatAI\App\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        $versions = ['v1'];

        foreach ($versions as $version) {
            Route::prefix("chat-ai/{$version}")
                ->middleware('api')
                ->namespace("{$this->moduleNamespace}\\Api\\" . strtoupper($version))
                ->group(module_path('ChatAI', "/routes/api_{$version}.php"));
        }
    }
}
