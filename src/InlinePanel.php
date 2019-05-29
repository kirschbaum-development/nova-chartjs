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
     * @param bool $showLabel
     * @param bool $isNotEditable
     */
    public function __construct(Resource $resource, Request $request, $panelTitle = 'Chart Metric Values', $showLabel = false, $isNotEditable = false)
    {
        parent::__construct(
            $panelTitle,
            $this->prepareFields($this->fields($resource->resource, $request, $panelTitle, $showLabel, $isNotEditable))
        );
    }

    /**
     * Fields for the inline chart panel.
     *
     * @param Chartable $chartable
     * @param Request $request
     * @param mixed $panelTitle
     * @param bool $showLabel
     * @param bool $isNotEditable
     *
     * @return array
     */
    protected function fields(Chartable $chartable, Request $request, $panelTitle = 'Chart Metric Values', $showLabel = false, $isNotEditable = false): array
    {
        $field = NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable) {
            return $chartable->novaChartjsMetricValue->metric_values ?? [];
        })->chartable($chartable ?? App::make($request->viaResource()::$model));

        if ($showLabel) {
            $field->showLabel();
        }

        if ($isNotEditable) {
            $field->isNotEditable();
        }

        return [
            $field,
        ];
    }
}
