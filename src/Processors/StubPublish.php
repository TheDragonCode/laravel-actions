<?php

declare(strict_types=1);

namespace DragonCode\LaravelActions\Processors;

use DragonCode\Support\Facades\Filesystem\File;

class StubPublish extends Processor
{
    protected string $stub = __DIR__ . '/../../resources/stubs/action.stub';

    public function handle(): void
    {
        $this->allow()
            ? $this->notification->task('Publishing', fn () => $this->publish())
            : $this->notification->info('Nothing to publish');
    }

    protected function publish(): void
    {
        File::copy($this->stub, $this->path());
    }

    protected function allow(): bool
    {
        return $this->options->force || $this->doesntExist();
    }

    protected function doesntExist(): bool
    {
        return ! File::exists($this->path());
    }

    protected function path(): string
    {
        return base_path('stubs/action.stub');
    }
}
