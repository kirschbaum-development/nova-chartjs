<?php

namespace KirschbaumDevelopment\NovaChartjs;

use Illuminate\Support\Str;
use KirschbaumDevelopment\NovaChartjs\Contracts\NovaChartjsChartable;
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
     * Pass chartable model to NovaChartjs to fetch settings
     *
     * @param NovaChartjsChartable|null $chartable
     *
     * @return NovaChartjs
     */
    public function chartable(NovaChartjsChartable $chartable = null): self
    {
        if ($chartable) {
            $chartableClass = get_class($chartable);

            $this->withMeta([
                'settings' => $chartableClass::getNovaChartjsSettings(),
                'label' => Str::singular(Str::title(Str::snake(class_basename($chartableClass), ' '))),
            ]);
        }

        return $this;
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
