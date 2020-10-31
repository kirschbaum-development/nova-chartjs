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

    /**
     * Return a list of all models available to be shown in a card.
     *
     * @param string $chartName
     * @param string $cardName
     *
     * @return array
     */
    public static function getCardDatasets($chartName = 'default', $cardName = ''): array
    {
        return self::getNovaChartjsComparisonData($chartName);
    }

    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public static function getNovaChartjsCardSettings($chartName = 'default'): array
    {
        return array_merge(
            [
                'type' => 'bar',
                'titleProp' => 'name',
                'identProp' => 'id',
                'indexColor' => '#999999',
                'color' => '#FF0000',
                'options' => ['responsive' => true, 'maintainAspectRatio' => false],
            ],
            data_get(self::getNovaChartjsSettings(), $chartName, []),
            [
                'height' => 200,
                'options' => ['responsive' => true, 'maintainAspectRatio' => false, 'legend' => ['position' => 'left', 'align' => 'middle']],
            ]
        );
    }

    /**
     * Return a list of additional datasets added to card.
     *
     * @return array
     */
    public static function getAdditionalCardDatasets($chartName = 'default'): array
    {
        return data_get((new self())->getAdditionalDatasets(), $chartName, []);
    }
}
