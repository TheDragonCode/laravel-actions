<?php

declare(strict_types=1);

namespace DragonCode\LaravelActions\Database;

use DragonCode\LaravelActions\Concerns\Database;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

abstract class BaseChangeMigrationColumn extends Migration
{
    use Database;

    public function up()
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->renameColumn('migration', 'action');

            $table->unsignedInteger('batch')->change();
        });
    }

    public function down()
    {
        Schema::table($this->getTableName(), function (Blueprint $table) {
            $table->renameColumn('action', 'migration');

            $table->integer('batch')->change();
        });
    }
}