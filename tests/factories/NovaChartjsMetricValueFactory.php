<?php

use Faker\Generator as Faker;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

$factory->define(NovaChartjsMetricValue::class, function (Faker $faker) {
    return [
        'chart_name' => 'default',
        'metric_values' => [
            'Banana' => $faker->numberBetween(0, 100),
            'Apple' => $faker->numberBetween(0, 100),
            'Pear' => $faker->numberBetween(0, 100),
        ],
    ];
});
