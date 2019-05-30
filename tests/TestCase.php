<?php

namespace KirschbaumDevelopment\NovaChartjs\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var \KirschbaumDevelopment\NovaChartjs\Tests\Chartable */
    protected $testChartable;

    /**
     * Setup the Tests.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(realpath(dirname(__DIR__) . '/tests/factories'));

        $this->loadMigrationsFrom(realpath(dirname(__DIR__)) . '/migrations');

        $this->setUpDatabase($this->app);

        $this->createTestModels();

        $this->testChartable = Chartable::first();
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('chartables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });
    }

    /**
     * Create Chartable Model for Testing.
     */
    protected function createTestModels(): void
    {
        Chartable::create(['name' => 'TestChartable']);
    }
}
