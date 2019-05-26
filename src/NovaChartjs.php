<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

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
     * @param mixed $chartable
     * @param string $name
     * @param null $attribute
     * @param callable|null $resolveCallback
     */
    public function __construct($chartable, $name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $chartableClass = get_class($chartable);

        if ($chartableClass) {
            $this->withMeta([
                'settings' => $chartableClass::getNovaChartjsSettings(),
                'label' => Str::singular(Str::title(Str::snake(class_basename($chartableClass), ' '))),
            ]);
        }
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
