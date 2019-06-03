<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests\Unit;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KirschbaumDevelopment\NovaChartjs\Tests\TestCase;
use KirschbaumDevelopment\NovaChartjs\Tests\Chartable;
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
    public function a_chartable_can_return_his_metric_value()
    {
        $this->addNovaChartjsMetricValueToChartable();

        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue);
    }

    /** @test **/
    public function a_chartable_can_automatically_create_metric_values_in_relationship_if_passed_before_creating()
    {
        $chartable = new Chartable(['name' => 'Unsaved Chartable']);
        $testArray = ['January' => 10, 'February' => 30];
        $chartable->novaChartjsMetricValue = $testArray;

        $this->assertNull($chartable->novaChartjsMetricValue);
        $chartable->save();
        $this->assertInstanceOf(NovaChartjsMetricValue::class, $chartable->fresh()->novaChartjsMetricValue);
        $this->assertEquals($testArray, $chartable->fresh()->novaChartjsMetricValue->metric_values);
    }

    /** @test **/
    public function a_chartable_can_automatically_create_new_metric_values_in_relationship_if_needed_and_passed_before_updating()
    {
        $this->assertNull($this->testChartable->novaChartjsMetricValue);

        $testArray = ['January' => 10, 'February' => 30];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->fresh()->novaChartjsMetricValue);
        $this->assertEquals($testArray, $this->testChartable->fresh()->novaChartjsMetricValue->metric_values);
    }

    /** @test **/
    public function a_chartable_can_automatically_update_metric_values_in_relationship_if_passed_before_updating()
    {
        $metricValue = factory(NovaChartjsMetricValue::class)->make();
        $this->addNovaChartjsMetricValueToChartable($metricValue);
        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue);
        $this->assertEquals($metricValue->metric_values, $this->testChartable->novaChartjsMetricValue->metric_values);

        $testArray = ['January' => 10, 'February' => 30];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        $this->assertNotEquals($metricValue->metric_values, $this->testChartable->fresh()->novaChartjsMetricValue->metric_values);
        $this->assertEquals($testArray, $this->testChartable->fresh()->novaChartjsMetricValue->metric_values);
    }

    /**
     * Adds a NovaChartjsMetricValue to a Chartable Model.
     *
     * @param NovaChartjsMetricValue|null $metricValue
     * @param Chartable|null $chartable
     */
    protected function addNovaChartjsMetricValueToChartable(NovaChartjsMetricValue $metricValue = null, Chartable $chartable = null): void
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
