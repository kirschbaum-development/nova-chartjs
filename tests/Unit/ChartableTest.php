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

        $this->assertCount(0, $this->testChartable->novaChartjsMetricValue);
        $this->addNovaChartjsMetricValueToChartable($firstMetricValue);
        $this->testChartable->fresh();
        $this->assertNotNull($this->testChartable->novaChartjsMetricValue()->where('chart_name', 'default')->first());

        $this->expectException(QueryException::class);
        $this->addNovaChartjsMetricValueToChartable($secondMetricValue);
    }

    public function testChartableCanReturnHisMetricValue()
    {
        $this->addNovaChartjsMetricValueToChartable();

        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue()->first());
    }

    public function testChartableCanAutomaticallyCreateMetricValuesInRelationshipIfPassedBeforeCreating()
    {
        $chartable = new Chartable(['name' => 'Unsaved Chartable']);
        $testArray = ['chartName' => 'default', 'chartValue' => ['January' => 10, 'February' => 30]];
        $chartable->novaChartjsMetricValue = $testArray;

        $this->assertCount(0, $this->testChartable->novaChartjsMetricValue);
        $chartable->save();

        tap($chartable->fresh(), function ($chartable) use ($testArray) {
            $this->assertInstanceOf(NovaChartjsMetricValue::class, $chartable->novaChartjsMetricValue()->first());
            $this->assertEquals($testArray['chartValue'], $chartable->novaChartjsMetricValue()->first()->metric_values);
        });
    }

    public function testChartableCanAutomaticallyCreateNewMetricValuesInRelationshipIfNeededAndPassedBeforeUpdating()
    {
        $this->assertCount(0, $this->testChartable->novaChartjsMetricValue);

        $testArray = ['chartName' => 'default', 'chartValue' => ['January' => 10, 'February' => 30]];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        tap($this->testChartable->fresh(), function ($chartable) use ($testArray) {
            $this->assertInstanceOf(NovaChartjsMetricValue::class, $chartable->novaChartjsMetricValue()->first());
            $this->assertEquals($testArray['chartValue'], $chartable->novaChartjsMetricValue()->first()->metric_values);
        });
    }

    public function testChartableCanAutomaticallyUpdateMetricValuesInRelationshipIfPassedBeforeUpdating()
    {
        $metricValue = factory(NovaChartjsMetricValue::class)->make();
        $this->addNovaChartjsMetricValueToChartable($metricValue);
        $this->assertInstanceOf(NovaChartjsMetricValue::class, $this->testChartable->novaChartjsMetricValue()->first());
        $this->assertEquals($metricValue->metric_values, $this->testChartable->novaChartjsMetricValue()->first()->metric_values);

        $testArray = ['chartName' => 'default', 'chartValue' => ['January' => 10, 'February' => 30]];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        tap($this->testChartable->fresh(), function ($chartable) use ($testArray, $metricValue) {
            $this->assertNotEquals($metricValue->metric_values, $chartable->novaChartjsMetricValue()->first()->metric_values);
            $this->assertEquals($testArray['chartValue'], $chartable->novaChartjsMetricValue()->first()->metric_values);
        });
    }

    public function testChartableAdditionOfMoreThanOneMetricValues()
    {
        $metricValue = factory(NovaChartjsMetricValue::class)->make();
        $this->addNovaChartjsMetricValueToChartable($metricValue);
        $secondaryMetricValue = factory(NovaChartjsMetricValue::class)->make(['chart_name' => 'second']);
        $this->addNovaChartjsMetricValueToChartable($secondaryMetricValue);

        $this->assertCount(2, $this->testChartable->novaChartjsMetricValue);
    }

    public function testChartableCanUpdateTheRightInstaceOfMetricValues()
    {
        $metricValue = factory(NovaChartjsMetricValue::class)->make();
        $this->addNovaChartjsMetricValueToChartable($metricValue);
        $secondaryMetricValue = factory(NovaChartjsMetricValue::class)->make(['chart_name' => 'second']);
        $this->addNovaChartjsMetricValueToChartable($secondaryMetricValue);

        $this->assertCount(2, $this->testChartable->novaChartjsMetricValue);

        $testArray = ['chartName' => 'second', 'chartValue' => ['January' => 10, 'February' => 30]];
        $this->testChartable->novaChartjsMetricValue = $testArray;
        $this->testChartable->save();

        tap($this->testChartable->fresh(), function ($chartable) use ($secondaryMetricValue, $testArray) {
            $this->assertNotEquals($secondaryMetricValue->metric_values, $chartable->novaChartjsMetricValue()->where('chart_name', 'second')->first()->metric_values);
            $this->assertEquals($testArray['chartValue'], $chartable->novaChartjsMetricValue()->where('chart_name', 'second')->first()->metric_values);
        });
    }

    protected function tearDown(): void
    {
        /**
         * We delete all entries to avoid SQL integrity constraint error
         * when migrating down and creating a unique index.
         */
        NovaChartjsMetricValue::truncate();
        parent::tearDown();
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

        $chartable->novaChartjsMetricValue()->create($metricValue->toArray());
    }
}
