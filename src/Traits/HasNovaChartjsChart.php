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
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    abstract public function getNovaChartjsSettings(): array;
}
