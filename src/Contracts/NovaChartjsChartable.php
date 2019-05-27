<?php

namespace KirschbaumDevelopment\NovaChartjs\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NovaChartjsChartable
{
    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public static function getNovaChartjsSettings(): array;

    /**
     * Return a list of all models available for comparison to root model
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function comparisonData(): Collection;
}
