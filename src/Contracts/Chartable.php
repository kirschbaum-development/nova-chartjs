<?php

namespace KirschbaumDevelopment\NovaChartjs\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Chartable
{
    /**
     * @return MorphMany
     */
    public function novaChartjsMetricValue(): MorphMany;

    /**
     * Should return settings for Nova Chart in prescribed format.
     *
     * @return array
     */
    public static function getNovaChartjsSettings(): array;

    /**
     * Return a list of all models available for comparison to root model.
     *
     * @param string $chartName
     *
     * @return array
     */
    public static function getNovaChartjsComparisonData($chartName = 'default'): array;

    /**
     * Return a list of additional datasets added to chart.
     *
     * @return array
     */
    public function getAdditionalDatasets(): array;
}
