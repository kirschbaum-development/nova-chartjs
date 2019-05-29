<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\MorphOne;
use KirschbaumDevelopment\NovaChartjs\Nova\MetricValue;

class RelationshipPanel extends Panel
{
    /**
     * Create a new panel instance containing NovaChartjsChart.
     *
     * @param string $panelTitle
     */
    public function __construct($panelTitle = 'Chart Metric Values')
    {
        parent::__construct(
            $panelTitle,
            $this->prepareFields($this->fields($panelTitle))
        );
    }

    /**
     * Fields for the chart panel.
     *
     * @param mixed $panelTitle
     *
     * @return array
     */
    protected function fields($panelTitle = 'Chart Metric Values'): array
    {
        return [
            MorphOne::make($panelTitle, 'novaChartjsMetricValue', MetricValue::class),
        ];
    }
}
