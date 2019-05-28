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
     * Mutator to set Metric Values from Chartable model
     *
     * @param $value
     */
    public function setNovaChartjsMetricValueAttribute($value): void
    {
        if (! $this->novaChartjsMetricValue) {
            $novaChartjsMetricValue = new NovaChartjsMetricValue(['metric_values' => $value]);
            $this->novaChartjsMetricValue()->save($novaChartjsMetricValue);
        } else {
            $this->novaChartjsMetricValue->metric_values = $value;
            $this->novaChartjsMetricValue->save();
        }
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
