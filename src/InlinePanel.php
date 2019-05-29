<?php

namespace KirschbaumDevelopment\NovaChartjs;

use App\Nova\Resource;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use KirschbaumDevelopment\NovaChartjs\Contracts\Chartable;

class InlinePanel extends Panel
{
    /**
     * Create a new panel instance containing NovaChartjsChart.
     *
     * @param Resource $resource
     * @param Request $request
     * @param string $panelTitle
     * @param bool $hideLabel
     * @param bool $isUserEditable
     */
    public function __construct(Resource $resource, Request $request, $panelTitle = 'Chart Metric Values', $hideLabel = true, $isUserEditable = true)
    {
        parent::__construct(
            $panelTitle,
            $this->prepareFields($this->fields($resource->resource, $request, $panelTitle, $hideLabel, $isUserEditable))
        );
    }

    /**
     * Fields for the inline chart panel.
     *
     * @param Chartable $chartable
     * @param Request $request
     * @param mixed $panelTitle
     * @param bool $hideLabel
     * @param bool $isUserEditable
     *
     * @return array
     */
    protected function fields(Chartable $chartable, Request $request, $panelTitle = 'Chart Metric Values', $hideLabel = true, $isUserEditable = true): array
    {
        return [
            NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable) {
                return $chartable->novaChartjsMetricValue->metric_values ?? [];
            })->hideLabel($hideLabel)
                ->isUserEditable($isUserEditable)
                ->chartable($chartable ?? App::make($request->viaResource()::$model)),
        ];
    }
}
