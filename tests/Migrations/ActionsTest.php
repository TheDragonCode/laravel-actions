<?php

declare(strict_types=1);

namespace Tests\Migrations;

use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use DragonCode\LaravelActions\Constants\Names;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActionsTest extends TestCase
{
    public function testRunActionsAfterInstall(): void
    {
        if (! class_exists(Driver::class)) {
            $this->assertTrue(true);

            return;
        }

        DB::table('migrations')->truncate();

        Schema::connection($this->database)->dropIfExists($this->table);

        $this->assertDatabaseCount('migrations', 0);
        $this->assertDatabaseDoesntTable($this->table);

        $this->artisan(Names::INSTALL)->run();

        $this->assertDatabaseCount('migrations', 0);
        $this->assertDatabaseHasTable($this->table);

        $this->artisan('migrate')->run();

        $this->assertDatabaseCount('migrations', 2);
        $this->assertDatabaseHas('migrations', ['migration' => '2022_08_18_180137_change_migration_actions_table']);
        $this->assertDatabaseHas('migrations', ['migration' => '2023_01_21_172923_rename_migrations_actions_table_to_actions']);
    }
}
