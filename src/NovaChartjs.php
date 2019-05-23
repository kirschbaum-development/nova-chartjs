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

        // Fetching Nova Resource Class
        $novaResourceClass = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[2]['class'];

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
