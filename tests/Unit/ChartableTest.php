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

    public function testChartableCanHaveOnlyOneMetricValue()
    {
        $firstMetricValue = factory(NovaChartjsMetricValue::class)->make();
        $secondMetricValue = factory(NovaChartjsMetricValue::class)->make();

        $this->assertNull($this->testChartable->novaChartjsMetricValue);
        $this->addNovaChartjsMetricValueToChartable($firstMetricValue);
        $this->assertEquals($firstMetricValue->id, $this->testChartable->fresh()->novaChartjsMetricValue->id);

        $this->expectException(QueryException::class);
        $this->addNovaChartjsMetricValueToChartable($secondMetricValue);
    }

    public function testChartableCanReturnHisMetricValue()
    {
        $this->addNovaChartjsMetricValueToChartable();

        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue);
    }

    public function testChartableCanAutomaticallyCreateMetricValuesInRelationshipIfPassedBeforeCreating()
    {
        $chartable = new Chartable(['name' => 'Unsaved Chartable']);
        $testArray = ['January' => 10, 'February' => 30];
        $chartable->novaChartjsMetricValue = $testArray;

        $this->assertNull($chartable->novaChartjsMetricValue);
        $chartable->save();

        tap($chartable->fresh(), function ($chartable) use ($testArray) {
            $this->assertInstanceOf(NovaChartjsMetricValue::class, $chartable->novaChartjsMetricValue);
            $this->assertEquals($testArray, $chartable->novaChartjsMetricValue->metric_values);
        });
    }

    public function testChartableCanAutomaticallyCreateNewMetricValuesInRelationshipIfNeededAndPassedBeforeUpdating()
    {
        $this->assertNull($this->testChartable->novaChartjsMetricValue);

        $testArray = ['January' => 10, 'February' => 30];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        tap($this->testChartable->fresh(), function ($chartable) use ($testArray) {
            $this->assertInstanceOf(NovaChartjsMetricValue::class, $chartable->novaChartjsMetricValue);
            $this->assertEquals($testArray, $chartable->novaChartjsMetricValue->metric_values);
        });
    }

    public function testChartableCanAutomaticallyUpdateMetricValuesInRelationshipIfPassedBeforeUpdating()
    {
        $metricValue = factory(NovaChartjsMetricValue::class)->make();
        $this->addNovaChartjsMetricValueToChartable($metricValue);
        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue);
        $this->assertEquals($metricValue->metric_values, $this->testChartable->novaChartjsMetricValue->metric_values);

        $testArray = ['January' => 10, 'February' => 30];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        tap($this->testChartable->fresh(), function ($chartable) use ($testArray, $metricValue) {
            $this->assertNotEquals($metricValue->metric_values, $chartable->novaChartjsMetricValue->metric_values);
            $this->assertEquals($testArray, $chartable->novaChartjsMetricValue->metric_values);
        });
    }

    /**
     * Adds a NovaChartjsMetricValue to a Chartable Model.
     *
     * @param NovaChartjsMetricValue|null $metricValue
     * @param Chartable|null $chartable
     */
    protected function addNovaChartjsMetricValueToChartable(
        NovaChartjsMetricValue $metricValue = null,
        Chartable $chartable = null
    ): void {
        if (empty($metricValue)) {
            $metricValue = factory(NovaChartjsMetricValue::class)->make();
        }

        if (empty($chartable)) {
            $chartable = $this->testChartable;
        }

        $chartable->novaChartjsMetricValue()->save($metricValue);
    }
}
