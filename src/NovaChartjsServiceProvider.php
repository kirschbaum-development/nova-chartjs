<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;
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
}
