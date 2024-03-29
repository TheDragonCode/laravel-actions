<?php

declare(strict_types=1);

namespace DragonCode\LaravelActions\Processors;

use DragonCode\LaravelActions\Constants\Names;
use DragonCode\LaravelActions\Constants\Options;

class Reset extends Processor
{
    public function handle(): void
    {
        $this->rollback(
            $this->options->connection,
            $this->options->path,
            $this->count()
        );
    }

    protected function rollback(?string $connection, ?string $path, int $step): void
    {
        $this->runCommand(Names::ROLLBACK, [
            '--' . Options::CONNECTION => $connection,
            '--' . Options::PATH       => $path,
            '--' . Options::REALPATH   => true,
            '--' . Options::STEP       => $step,
            '--' . Options::FORCE      => true,
        ]);
    }

    protected function count(): int
    {
        return $this->repository->getLastBatchNumber();
    }
}
