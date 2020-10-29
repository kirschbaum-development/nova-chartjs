<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
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
     * @param bool $hideFromIndex
     */
    public function __construct(
        NovaResource $resource,
        Request $request,
        $panelTitle = 'Chart Metric Values',
        $showLabel = false,
        $notEditable = false,
        $hideFromIndex = false,
        $chartName = 'default'
    ) {
        parent::__construct(
            $panelTitle,
            $this->prepareFields(
                $this->fields($resource->resource, $request, $panelTitle, $showLabel, $notEditable, $hideFromIndex, $chartName)
            )
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
     * @param bool $hideFromIndex
     *
     * @return array
     */
    protected function fields(
        Chartable $chartable,
        Request $request,
        $panelTitle = 'Chart Metric Values',
        $showLabel = false,
        $notEditable = false,
        $hideFromIndex = false,
        $chartName = 'default'
    ): array {
        $field = NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable, $chartName) {
            return optional($chartable->novaChartjsMetricValue()->where('chart_name', $chartName)->first())->metric_values ?? [];
        });

        if ($showLabel) {
            $field->showLabel();
        }

        if ($notEditable) {
            $field->notEditable();
        }

        if ($hideFromIndex) {
            $field->hideFromIndex();
        }

        $field->chartName($chartName);

        return [$field];
    }
}
