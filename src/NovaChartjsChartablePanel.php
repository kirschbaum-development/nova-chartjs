<?php

namespace KirschbaumDevelopment\NovaChartjs;

use App\Nova\Resource;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use KirschbaumDevelopment\NovaChartjs\Contracts\NovaChartjsChartable;

class NovaChartjsChartablePanel extends Panel
{
    /**
     * Create a new panel instance containing NovaChartjsChart.
     *
     * @param Resource $resource
     * @param Request $request
     * @param string $panelTitle
     */
    public function __construct(Resource $resource, Request $request, $panelTitle = 'Chart Metric Values')
    {
        parent::__construct(
            $panelTitle,
            $this->prepareFields($this->fields($resource->resource, $request, $panelTitle))
        );
    }

    /**
     * Fields for the inline chart panel.
     *
     * @param NovaChartjsChartable $chartable
     * @param Request $request
     * @param mixed $panelTitle
     *
     * @return array
     */
    protected function fields(NovaChartjsChartable $chartable, Request $request, $panelTitle = 'Chart Metric Values'): array
    {
        return [
            NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable) {
                return $chartable->novaChartjsMetricValue->metric_values ?? [];
            })->hideWhenCreating()
                ->hideLabel()
                ->chartable($chartable ?? App::make($request->viaResource()::$model)),
        ];
    }
}
