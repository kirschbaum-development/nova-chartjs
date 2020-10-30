<?php

namespace KirschbaumDevelopment\NovaChartjs\Nova;

use Laravel\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphTo;
use KirschbaumDevelopment\NovaChartjs\NovaChartjs;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

class MetricValue extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = NovaChartjsMetricValue::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        $field = NovaChartjs::make('Metric Values', 'metric_values')
            ->rules('required', 'json');
        $field->showOnCreation = true;
        $field->chartName($this->chart_name ?? 'default');

        return [
            $field,
            Text::make('Chart Name'),
            MorphTo::make('Chartable')->onlyOnIndex(),
        ];
    }
}
