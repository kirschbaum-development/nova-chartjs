<?php

namespace KirschbaumDevelopment\NovaChartjs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

trait HasChart
{
    /** @var array */
    protected $unsavedMetricValues = [];

    /**
     * Get the Chartable Model's metric values.
     *
     * @return MorphMany
     */
    public function novaChartjsMetricValue(): MorphMany
    {
        return $this->MorphMany(NovaChartjsMetricValue::class, 'chartable');
    }

    /**
     * Delete a models chart data before model is deleted.
     */
    public static function bootHasChart()
    {
        static::deleting(function ($model) {
            if ($model->novaChartjsMetricValue) {
                $model->novaChartjsMetricValue->each->delete();
            }
        });

        static::created(function ($model) {
            collect($model->unsavedMetricValues)->each(function ($chartData) use ($model) {
                $model->novaChartjsMetricValue()->create($chartData);
            });
        });
    }

    /**
     * Mutator to set Metric Values from Chartable model.
     *
     * @param $value
     */
    public function setNovaChartjsMetricValueAttribute($value): void
    {
        $chartName = data_get($value, 'chartName', 'default');
        $chartValue = data_get($value, 'chartValue', []);

        $chartInstance = $this->novaChartjsMetricValue()->where('chart_name', $chartName)->first();

        if (empty($chartInstance)) {
            $this->getKey()
                ? $this->novaChartjsMetricValue()->create([
                    'metric_values' => $chartValue,
                    'chart_name' => $chartName,
                ])
                : $this->unsavedMetricValues[] = [
                    'metric_values' => $chartValue,
                    'chart_name' => $chartName,
                ];

            return;
        }

        $chartInstance->metric_values = $chartValue;
        $chartInstance->save();
    }

    /**
     * Return a list of all models available for comparison to root model.
     *
     * @param string $chartName
     *
     * @return array
     */
    public static function getNovaChartjsComparisonData($chartName = 'default'): array
    {
        $resources = static::query()
            ->has('novaChartjsMetricValue')
            ->get();

        $charts = NovaChartjsMetricValue::query()
            ->whereIn('chartable_id', $resources->pluck('id'))
            ->where('chartable_type', static::class)
            ->where('chart_name', $chartName)
            ->toBase()
            ->get();

        return $resources->map(function ($resource) use ($charts) {
            $data = optional($charts->first(function ($chart) use ($resource) {
                return $chart->chartable_id === $resource->id;
            }))->metric_values;

            $resource->setAttribute(
                'novaChartjsComparisonData',
                $data ? json_decode($data, true) : null,
            );

            return $resource;
        })
        ->values()
        ->toArray();
    }

    /**
     * Return a list of additional datasets added to chart.
     *
     * @return array
     */
    public function getAdditionalDatasets(): array
    {
        return [
            'default' => [],
        ];
    }
}
