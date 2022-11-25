<?php

namespace KirschbaumDevelopment\NovaChartjs\Http\Controllers;

use Illuminate\Routing\Controller;

class GetAdditionalDatasetsController extends Controller
{
    public function __invoke()
    {
        $field = request()->all();
        $resource = resolve($field["model"])->find($field["ident"]);

        return response()->json([
            'additionalDatasets' => data_get(
                $resource->getAdditionalDatasets(), 
                $field["chartName"], 
                []
            ),
        ]);
    }
}
