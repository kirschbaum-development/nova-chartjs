<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use KirschbaumDevelopment\NovaChartjs\Contracts\Chartable;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

class NovaChartjs extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-chartjs';

    /**
     * Create a new Nova Chartjs field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'showLabel' => false,
            'notEditable' => false,
        ]);
    }

    /**
     * @deprecated
     *
     * This method has been depricated and will be removed in next major update.
     *
     * Pass chartable model to NovaChartjs to fetch settings.
     *
     * @param Chartable|null $chartable
     *
     * @return NovaChartjs
     */
    public function chartable(Chartable $chartable): self
    {
        return $this;
    }

    /**
     * Resolve the field's value.
     *
     * @param  mixed  $resource
     * @param  string|null  $attribute
     */
    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        if ($resource instanceof NovaChartjsMetricValue) {
            $resource = $resource->chartable;
        }

        if (! empty($resource)) {
            $settings = $resource::getNovaChartjsSettings();

            $this->withMeta([
                'settings' => $settings,
                'comparison' => $resource::getNovaChartjsComparisonData(),
                'additionalDatasets' => $resource->getAdditionalDatasets() ?? [],
                'model' => Str::singular(Str::title(Str::snake(class_basename($resource), ' '))),
                'title' => $this->getChartableProp($resource, $settings['titleProp'] ?? $resource->getKeyName()),
                'ident' => $this->getChartableProp($resource, $settings['identProp'] ?? $resource->getKeyName()),
            ]);
        }
    }

    /**
     * Hide Label to make Chart occupy full width.
     *
     * @return NovaChartjs
     */
    public function showLabel(): self
    {
        return $this->withMeta([
            'showLabel' => true,
        ]);
    }

    /**
     * set whether a user can edit a model data.
     *
     * @return NovaChartjs
     */
    public function notEditable(): self
    {
        $this->hideWhenUpdating()
            ->hideFromIndex();

        return $this->withMeta([
            'notEditable' => true,
        ]);
    }

    /**
     * Fetch a property from Chartable.
     *
     * @param Chartable $chartable
     * @param string $prop
     *
     * @return string
     */
    public function getChartableProp(Chartable $chartable, string $prop = 'id'): string
    {
        return $chartable->{$prop} ?? 'Unknown';
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     *
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $value = json_decode($request[$requestAttribute], true);
            $model->{$attribute} = $this->isNullValue($value) ? null : $value;
        }
    }
}
