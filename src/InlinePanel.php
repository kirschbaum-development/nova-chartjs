<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Resource as NovaResource;
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
        $chartName = 'default'
    ) {
        parent::__construct(
            $panelTitle,
            $this->prepareFields(
                $this->fields($resource->resource, $request, $panelTitle, $chartName)
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
        $chartName = 'default'
    ): array {
        $field = NovaChartjs::make($panelTitle, 'novaChartjsMetricValue', function () use ($chartable, $chartName) {
            return optional(
                $chartable->novaChartjsMetricValue()
                    ->where('chart_name', $chartName)
                    ->first()
            )->metric_values ?? [];
        });

        $field->chartName($chartName);

        return [$field];
    }

    /**
     * Specify that the fields should be hidden from the index view.
     *
     * @return $this
     */
    public function hideFromIndex()
    {
        foreach ($this->data as $field) {
            $field->hideFromIndex();
        }

        return $this;
    }

    /**
     * Specify that the fields should be editable.
     *
     * @return $this
     */
    public function notEditable()
    {
        foreach ($this->data as $field) {
            $field->notEditable();
        }

        return $this;
    }

    /**
     * Specify that the fields should show the label.
     *
     * @return $this
     */
    public function showLabel()
    {
        foreach ($this->data as $field) {
            $field->showLabel();
        }

        return $this;
    }
}
