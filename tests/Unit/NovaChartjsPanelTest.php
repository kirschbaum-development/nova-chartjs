<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests\Unit;

use PHPUnit\Framework\TestCase;
use KirschbaumDevelopment\NovaChartjs\NovaChartjsPanel;
use KirschbaumDevelopment\NovaChartjs\Exceptions\InvalidNovaResource;
use KirschbaumDevelopment\NovaChartjs\Exceptions\MissingNovaResource;

class NovaChartjsPanelTest extends TestCase
{
    /** @test **/
    public function a_nova_chartjs_panel_throws_an_exception_if_nova_resource_is_not_passed_to_it()
    {
        $this->expectException(MissingNovaResource::class);
        new NovaChartjsPanel(null);
    }

    /** @test **/
    public function a_nova_chartjs_panel_throws_an_exception_if_an_invalid_nova_resource_is_not_passed_to_it()
    {
        $this->expectException(InvalidNovaResource::class);
        new NovaChartjsPanel('random');
    }
}
