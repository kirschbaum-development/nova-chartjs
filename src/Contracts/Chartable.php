<?php

namespace KirschbaumDevelopment\NovaChartjs\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Chartable
{
    /**
     * @return MorphOne
     */
    public function novaChartjsMetricValue(): MorphOne;

    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public static function getNovaChartjsSettings(): array;

    /**
     * Return a list of all models available for comparison to root model
     *
     * @return array
     */
    public static function getNovaChartjsComparisonData(): array;

    /**
     * Return a list of additional datasets added to chart
     *
     * @return array
     */
    public function getAdditionalDatasets(): array;
}
