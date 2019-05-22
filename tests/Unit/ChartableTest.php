<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests\Unit;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KirschbaumDevelopment\NovaChartjs\Tests\TestCase;
use KirschbaumDevelopment\NovaChartjs\Models\NovaChartjsMetricValue;

class ChartableTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_chartable_can_have_only_one_metric_value()
    {
        $firstMetricValue = factory(NovaChartjsMetricValue::class)->make();
        $secondMetricValue = factory(NovaChartjsMetricValue::class)->make();

        $this->assertNull($this->testChartable->novaChartjsMetricValue);
        $this->addNovaChartjsMetricValueToChartable($firstMetricValue);
        $this->assertEquals($firstMetricValue->id, $this->testChartable->fresh()->novaChartjsMetricValue->id);

        $this->expectException(QueryException::class);
        $this->addNovaChartjsMetricValueToChartable($secondMetricValue);
    }

    /** @test **/
    public function a_chartable_can_return_his_metric_values()
    {
        $this->addNovaChartjsMetricValueToChartable();

        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue()->first());
    }

    /**
     * Adds a NovaChartjsMetricValue to a Chartable Model
     *
     * @param NovaChartjsMetricValue|null $metricValue
     * @param Chartable|null $chartable
     */
    protected function addNovaChartjsMetricValueToChartable(NovaChartjsMetricValue $metricValue = null, Chartable $chartable = null):void
    {
        if (empty($metricValue)) {
            $metricValue = factory(NovaChartjsMetricValue::class)->make();
        }

        if (empty($chartable)) {
            $chartable = $this->testChartable;
        }

        $chartable->novaChartjsMetricValue()->save($metricValue);
    }
}
