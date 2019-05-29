<?php

namespace KirschbaumDevelopment\NovaChartjs\Traits;

use Laravel\Nova\Http\Requests\NovaRequest;

trait RedirectsOnCreate
{
    /**
     * Return the location to redirect the user after creation.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \App\Nova\Resource $resource
     *
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey() . '/' . $resource->getKey() . '/edit';
    }
}
