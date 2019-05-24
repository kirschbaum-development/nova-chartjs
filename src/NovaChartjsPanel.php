<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;
use Illuminate\Support\Arr;
use KirschbaumDevelopment\NovaChartjs\Exceptions\InvalidNovaResource;
use KirschbaumDevelopment\NovaChartjs\Exceptions\MissingNovaResource;

class NovaChartjsPanel extends Panel
{
    /**
     * Create a new panel instance containing NovaChartjsChart.
     *
     * @param mixed $resource
     * @param string $panelTitle
     * @param string $chartTitle
     *
     * @throws \Throwable
     *
     * @return void
     *
     */
    public function __construct($resource, $panelTitle = '', $chartTitle = '')
    {
        throw_if(
            ! $resource,
            MissingNovaResource::create('Panel')
        );

        throw_if(
            ! property_exists($resource, 'model'),
            InvalidNovaResource::create('Panel')
        );

        parent::__construct(
            $panelTitle ?: Arr::get($resource::$model::getNovaChartjsSettings(), 'panelTitle', 'Nova Chartjs Chart'),
            $this->prepareFields($this->fields($resource, $chartTitle))
        );
    }

    /**
     * Fields for the chart panel.
     *
     * @param mixed $resource
     * @param string $chartTitle
     *
     * @return array
     */
    protected function fields($resource, $chartTitle = ''): array
    {
        return [
            NovaChartjs::make(
                $resource,
                $chartTitle ?: Arr::get($resource::$model::getNovaChartjsSettings(), 'title', 'Chart'),
                'novaChartjsMetricValue'
            ),
        ];
    }
}
