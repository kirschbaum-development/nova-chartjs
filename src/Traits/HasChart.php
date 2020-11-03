<?php

namespace KirschbaumDevelopment\NovaChartjs\Traits;

use Illuminate\Support\Collection;
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
     * Return a list of datapoints for each parameters.
     *
     * @param string $chartName
     * @param null|mixed $sortBy
     * @param mixed $limit
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getNovaChartjsParameterizedDataSet($chartName = 'default', $sortBy = null, $limit = 0): Collection
    {
        $parameters = data_get(static::getNovaChartjsSettings(), sprintf('%s.parameters', $chartName));

        $output = collect();

        $dataset = collect(static::getNovaChartjsComparisonData($chartName));

        if (! empty($sortBy)) {
            $dataset = $dataset->sortBy($sortBy)->values();
        }

        if ($limit > 0) {
            $dataset = $dataset->slice(0, $limit);
        }

        foreach ($parameters as $parameter) {
            $output->put($parameter, $dataset->pluck(sprintf('novaChartjsComparisonData.%s', $parameter)));
        }

        return $output;
    }

    /**
     * Returns a dataset consistint of max values for each parameter.
     *
     * @param string $chartName
     * @param null|mixed $sortBy
     * @param mixed $limit
     *
     * @return array
     */
    public static function getNovaChartjsMaxDataSet($chartName = 'default', $sortBy = null, $limit = 0): array
    {
        $dataset = static::getNovaChartjsParameterizedDataSet($chartName, $sortBy, $limit);

        return $dataset->map(function (Collection $datpoints) {
            return $datpoints->max();
        })->values()->toArray();
    }

    /**
     * Returns a dataset consistint of min values for each parameter.
     *
     * @param string $chartName
     * @param null|mixed $sortBy
     * @param mixed $limit
     *
     * @return array
     */
    public static function getNovaChartjsMinDataSet($chartName = 'default', $sortBy = null, $limit = 0): array
    {
        $dataset = static::getNovaChartjsParameterizedDataSet($chartName, $sortBy, $limit);

        return $dataset->map(function (Collection $datpoints) {
            return $datpoints->min();
        })->values()->toArray();
    }

    /**
     * Returns a dataset consistint of average values for each parameter.
     *
     * @param string $chartName
     * @param null|mixed $sortBy
     * @param mixed $limit
     *
     * @return array
     */
    public static function getNovaChartjsAvgDataSet($chartName = 'default', $sortBy = null, $limit = 0): array
    {
        $dataset = static::getNovaChartjsParameterizedDataSet($chartName, $sortBy, $limit);

        return $dataset->map(function (Collection $datpoints) {
            return $datpoints->avg();
        })->values()->toArray();
    }

    /**
     * Returns a dataset consistint of median values for each parameter.
     *
     * @param string $chartName
     * @param null|mixed $sortBy
     * @param mixed $limit
     *
     * @return array
     */
    public static function getNovaChartjsMedianDataSet($chartName = 'default', $sortBy = null, $limit = 0): array
    {
        $dataset = static::getNovaChartjsParameterizedDataSet($chartName, $sortBy, $limit);

        return $dataset->map(function (Collection $datpoints) {
            return $datpoints->median();
        })->values()->toArray();
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
        return static::with('novaChartjsMetricValue')
            ->has('novaChartjsMetricValue')
            ->get()
            ->map(function ($chartData) use ($chartName) {
                $chartData->setAttribute(
                    'novaChartjsComparisonData',
                    optional($chartData->novaChartjsMetricValue()->where('chart_name', $chartName)->first())->metric_values
                );

                return $chartData;
            })
            ->reject(function ($chartData) {
                return empty($chartData->novaChartjsComparisonData);
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
