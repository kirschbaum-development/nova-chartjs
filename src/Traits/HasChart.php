<?php

namespace KirschbaumDevelopment\NovaChartjs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

trait HasChart
{
    /** @var array */
    protected $unsavedMetricValues = [];

    /**
     * Get the Chartable Model's metric values.
     *
     * @return MorphOne
     */
    public function novaChartjsMetricValue(): MorphOne
    {
        return $this->morphOne(NovaChartjsMetricValue::class, 'chartable');
    }

    /**
     * Delete a models chart data before model is deleted.
     */
    public static function bootHasChart()
    {
        static::deleting(function ($model) {
            if ($model->novaChartjsMetricValue) {
                $model->novaChartjsMetricValue->delete();
            }
        });

        static::created(function ($model) {
            $model->novaChartjsMetricValue()->create(['metric_values' => $model->unsavedMetricValues]);
        });
    }

    /**
     * Mutator to set Metric Values from Chartable model.
     *
     * @param $value
     */
    public function setNovaChartjsMetricValueAttribute($value): void
    {
        if (! $this->novaChartjsMetricValue) {
            if ($this->getKey()) {
                $this->novaChartjsMetricValue()->create(['metric_values' => $value]);
            } else {
                $this->unsavedMetricValues = $value;
            }
        } else {
            $this->novaChartjsMetricValue->metric_values = $value;
            $this->novaChartjsMetricValue->save();
        }
    }

    /**
     * Return a list of all models available for comparison to root model.
     *
     * @return array
     */
    public static function getNovaChartjsComparisonData(): array
    {
        return static::with('novaChartjsMetricValue')
            ->has('novaChartjsMetricValue')
            ->get()
            ->toArray();
    }

    /**
     * Return a list of additional datasets added to chart.
     *
     * @return array
     */
    public function getAdditionalDatasets(): array
    {
        return [];
    }
}
