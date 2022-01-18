<?php

namespace Tests;

use DragonCode\LaravelActions\Concerns\Versionable;
use DragonCode\LaravelActions\ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Concerns\Actionable;
use Tests\Concerns\AssertDatabase;
use Tests\Concerns\Database;
use Tests\Concerns\Files;
use Tests\Concerns\Laraveable;
use Tests\Concerns\Settings;

abstract class TestCase extends BaseTestCase
{
    use Actionable;
    use AssertDatabase;
    use Database;
    use Files;
    use Laraveable;
    use RefreshDatabase;
    use Settings;
    use Versionable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setCache(false);

        $this->freshDatabase();
        $this->freshFiles();
    }

    protected function tearDown(): void
    {
        $this->artisan('config:clear')->run();
        $this->artisan('event:clear')->run();
        $this->artisan('route:clear')->run();
        $this->artisan('view:clear')->run();

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setTable($app);
        $this->setDatabase($app);
    }

    protected function table()
    {
        return DB::table($this->table);
    }
}
