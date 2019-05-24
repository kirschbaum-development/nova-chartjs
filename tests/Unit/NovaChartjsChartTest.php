<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests\Unit;

use PHPUnit\Framework\TestCase;
use KirschbaumDevelopment\NovaChartjs\NovaChartjs;
use KirschbaumDevelopment\NovaChartjs\Exceptions\InvalidNovaResource;
use KirschbaumDevelopment\NovaChartjs\Exceptions\MissingNovaResource;

class NovaChartjsChartTest extends TestCase
{
    /** @test **/
    public function a_nova_chartjs_chart_throws_an_exception_if_nova_resource_is_not_passed_to_it()
    {
        $this->expectException(MissingNovaResource::class);
        NovaChartjs::make(null, 'name');
    }

    /** @test **/
    public function a_nova_chartjs_chart_throws_an_exception_if_an_invalid_nova_resource_is_not_passed_to_it()
    {
        $this->expectException(InvalidNovaResource::class);
        NovaChartjs::make('random', 'name');
    }
}
