<?php

use Illuminate\Support\Facades\Route;
use KirschbaumDevelopment\NovaChartjs\Http\Controllers\GetAdditionalDatasetsController;
use KirschbaumDevelopment\NovaChartjs\Http\Controllers\RetrieveModelComparisonDataController;


Route::post('/retrieve-model-comparison-data', RetrieveModelComparisonDataController::class);
Route::post('/get-additional-datasets', GetAdditionalDatasetsController::class);
