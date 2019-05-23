<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Fields\Field;

class NovaChartjs extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-chartjs';

    /**
     * NovaChartjs constructor.
     * Extending parent constructor to inject MetaData from Model
     *
     * @param $name
     * @param null $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        // Confirm whether chart is used standalone of inside the NovaChartjsPanel class
        $isPanel = class_basename(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[2]['class']) == class_basename(NovaChartjsPanel::class);

        // Locate Nova Resource Class in backtrace
        $backtracePos = $isPanel ? 4 : 2;

        // Fetching Nova Resource Class
        $novaResourceClass = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[$backtracePos]['class'];

        if ($novaResourceClass) {
            $this->withMeta([
                // Fetching Settings enforce by HasNovaChartjsChart Trait
                'settings' => $novaResourceClass::$model::getNovaChartjsSettings(),
                // Fetching Preferred Label from NovaResourceClass
                'label' => $novaResourceClass::singularLabel(),
            ]);
        }
    }
}
