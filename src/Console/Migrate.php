<?php

namespace DragonCode\LaravelActions\Console;

use DragonCode\LaravelActions\Concerns\Database;
use DragonCode\LaravelActions\Concerns\Infoable;
use DragonCode\LaravelActions\Concerns\Optionable;
use DragonCode\LaravelActions\Constants\Names;
use Illuminate\Database\Console\Migrations\MigrateCommand as BaseCommand;

class Migrate extends BaseCommand
{
    use Database;
    use Infoable;
    use Optionable;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = Names::MIGRATE
    . ' {--database= : The database connection to use}'
    . ' {--force : Force the operation to run when in production}'
    . ' {--step : Force the actions to be run so they can be rolled back individually}'
    . ' {--path=* : The path(s) to the migrations files to be executed}'
    . ' {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the actions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->migrator->usingConnection($this->optionDatabase(), function () {
            $this->prepareDatabase();

            $this->migrator->setOutput($this->output)
                ->run($this->getMigrationPaths(), [
                    'step' => $this->optionStep(),
                ]);
        });

        return 0;
    }

    /**
     * Prepare the action database for running.
     */
    protected function prepareDatabase(): void
    {
        if (! $this->migrator->repositoryExists()) {
            $this->call(Names::INSTALL, array_filter([
                '--database' => $this->optionDatabase(),
            ]));
        }
    }

    protected function getMigrationPaths(): array
    {
        if ($paths = $this->optionPath()) {
            return $paths;
        }

        return array_merge(array_map(static function($path) {
            return $path.DIRECTORY_SEPARATOR.'actions';
        }, $this->migrator->paths()), [$this->getMigrationPath()]);
    }
}
