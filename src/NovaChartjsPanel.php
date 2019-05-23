<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;

class NovaChartjsPanel extends Panel
{
    /**
     * Create a new panel instance for NovaChartjsChart.
     *
     * @param mixed $name
     *
     * @return void
     */
    public function __construct($name = 'Nova Chartjs')
    {
        parent::__construct($name, $this->prepareFields($this->fields()));
    }

    /**
     * Fields for the comment panel.
     *
     * @return array
     */
    protected function fields(): array
    {
        return [
            NovaChartjs::make(
                'Chart',
                'novaChartjsMetricValue'
            ),
        ];
    }
}
