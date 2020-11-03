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
     * @param string $chartName
     */
    public function __construct(
        NovaResource $resource,
        Request $request,
        $panelTitle = 'Chart Metric Values',
        $showLabel = false,
        $notEditable = false,
        $hideFromIndex = false,
        $chartName = 'default',
        $isField = false
    ) {
        parent::__construct(
            $panelTitle,
            $this->prepareFields(
                $this->fields($resource->resource, $request, $panelTitle, $showLabel, $notEditable, $hideFromIndex, $chartName, $isField)
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
     * @param string $chartName
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
        $chartName = 'default',
        $isField = false
    ): array {
        $field = $this->getField($chartable, $panelTitle, $chartName, $isField);

        if ($showLabel) {
            $field->showLabel();
        }

        if ($notEditable) {
            $field->notEditable();
        }

        if ($hideFromIndex) {
            $field->hideFromIndex();
        }

        if($isField) {
            $field->isField();
        }

        $field->chartName($chartName);

        return [$field];
    }

    protected function getField(Chartable $chartable, $panelTitle = 'Chart Metric Values', $chartName = 'default', $isField = false): NovaChartjs  {
        if($isField) {
            return NovaChartjs::make($panelTitle, $chartName);
        }

        return NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable, $chartName) {
            return optional($chartable->novaChartjsMetricValue()->where('chart_name', $chartName)->first())->metric_values ?? [];
        });
    }
}
