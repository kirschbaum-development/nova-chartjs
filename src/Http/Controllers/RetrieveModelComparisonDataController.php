<?php

namespace KirschbaumDevelopment\NovaChartjs\Http\Controllers;

use Illuminate\Routing\Controller;

class RetrieveModelComparisonDataController extends Controller
{
    public function __invoke()
    {
        $request = request()->all();
        $resource = resolve(data_get($request, "field.model"))->find(data_get($request,"field.ident"));

        return response()->json([
            'comparison' => $resource::getNovaChartjsComparisonData(
                data_get($request, "field.chartName"),
                data_get($request, "searchFields"),
                data_get($request, "searchValue"),
            ),
        ]);
    }
}
