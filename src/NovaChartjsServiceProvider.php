<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use KirschbaumDevelopment\NovaChartjs\Nova\MetricValue;

class NovaChartjsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->loadMigrations();
        $this->registerResources();
        $this->serveField();
        $this->app->booted(function () {
            $this->routes();
        });
    }

    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    protected function serveField(): void
    {
        Nova::serving(function () {
            Nova::script('nova-chartjs', __DIR__ . '/../dist/js/field.js');
            Nova::style('nova-chartjs', __DIR__ . '/../dist/css/field.css');
        });
    }

    protected function registerResources(): void
    {
        Nova::resources([
            MetricValue::class,
        ]);
    }

    /**
     * Register package routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-chartjs')
            ->group(__DIR__.'/../routes/api.php');
    }
}
