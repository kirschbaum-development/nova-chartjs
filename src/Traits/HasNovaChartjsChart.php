<?php

namespace KirschbaumDevelopment\NovaChartjs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

trait HasNovaChartjsChart
{
    /**
     * Get the Chartable Model's metric values
     *
     * @return MorphOne
     */
    public function novaChartjsMetricValue(): MorphOne
    {
        return $this->morphOne(NovaChartjsMetricValue::class, 'chartable');
    }

    /**
     * Return a list of all models available for comparison to root model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNovaChartjsComparisonData(): array
    {
        return static::with('novaChartjsMetricValue')
            ->has('novaChartjsMetricValue')
            ->get()
            ->toArray();
    }
}
