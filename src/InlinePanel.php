<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Nova\Resource as NovaResource;
use KirschbaumDevelopment\NovaChartjs\Contracts\Chartable;

class InlinePanel extends Panel
{
    /**
     * Create a new panel instance containing NovaChartjsChart.
     *
     * @param NovaResource $resource
     * @param Request $request
     * @param string $panelTitle
     * @param bool $showLabel
     * @param bool $notEditable
     */
    public function __construct(NovaResource $resource, Request $request, $panelTitle = 'Chart Metric Values', $showLabel = false, $notEditable = false)
    {
        parent::__construct(
            $panelTitle,
            $this->prepareFields($this->fields($resource->resource, $request, $panelTitle, $showLabel, $notEditable))
        );
    }

    /**
     * Fields for the inline chart panel.
     *
     * @param Chartable $chartable
     * @param Request $request
     * @param mixed $panelTitle
     * @param bool $showLabel
     * @param bool $notEditable
     *
     * @return array
     */
    protected function fields(Chartable $chartable, Request $request, $panelTitle = 'Chart Metric Values', $showLabel = false, $notEditable = false): array
    {
        $field = NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable) {
            return $chartable->novaChartjsMetricValue->metric_values ?? [];
        })->chartable($chartable ?? App::make($request->viaResource()::$model));

        if ($showLabel) {
            $field->showLabel();
        }

        if ($notEditable) {
            $field->notEditable();
        }

        return [
            $field,
        ];
    }
}
