<?php

declare(strict_types=1);

namespace DragonCode\LaravelActions\Processors;

use DragonCode\LaravelActions\Constants\Names;
use DragonCode\LaravelActions\Constants\Options;

class Fresh extends Processor
{
    public function handle(): void
    {
        $this->drop();
        $this->actions();
    }

    protected function drop(): void
    {
        if ($this->repository->repositoryExists()) {
            $this->notification->task('Dropping all actions', fn () => $this->repository->deleteRepository());
        }
    }

    protected function actions(): void
    {
        $this->runCommand(Names::ACTIONS, [
            '--' . Options::CONNECTION => $this->options->connection,
            '--' . Options::PATH       => $this->options->path,
            '--' . Options::REALPATH   => true,
        ]);
    }
}
