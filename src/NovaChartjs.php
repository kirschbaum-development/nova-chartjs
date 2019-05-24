<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Laravel\Nova\Fields\Field;
use KirschbaumDevelopment\NovaChartjs\Exceptions\InvalidNovaResource;
use KirschbaumDevelopment\NovaChartjs\Exceptions\MissingNovaResource;

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
     * @param string $name
     * @param null $attribute
     * @param callable|null $resolveCallback
     * @param mixed $resource
     *
     * @throws \Throwable
     */
    public function __construct($resource, $name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        throw_if(
            ! $resource,
            MissingNovaResource::create('Chart')
        );

        throw_if(
            ! property_exists($resource, 'model'),
            InvalidNovaResource::create('Chart')
        );

        $this->withMeta([
            'settings' => $resource::$model::getNovaChartjsSettings(),
            'label' => $resource::singularLabel(),
        ]);
    }
}
