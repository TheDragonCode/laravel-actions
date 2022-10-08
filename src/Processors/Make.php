<?php

declare(strict_types=1);

namespace DragonCode\LaravelActions\Processors;

use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Filesystem\Path;
use DragonCode\Support\Facades\Helpers\Str;

class Make extends Processor
{
    protected string $fallbackName = 'auto';

    protected string $stub = __DIR__ . '/../../resources/stubs/action.stub';

    public function handle(): void
    {
        $name = $this->getName();
        $path = $this->getPath($name);

        $this->create($path);
    }

    protected function create(string $path): void
    {
        File::copy($this->stub, $path);
    }

    protected function getPath(string $name): string
    {
        return $this->options->realpath ? $name : $this->config->path($name);
    }

    protected function getName(): string
    {
        $branch   = $this->getBranchName();
        $filename = $this->getFilename($branch);

        return Path::dirname($branch) . DIRECTORY_SEPARATOR . $filename;
    }

    protected function getFilename(string $branch): string
    {
        return Str::of(Path::filename($branch))->prepend($this->getTime())->end('.php')->toString();
    }

    protected function getBranchName(): string
    {
        return $this->options->name ?? $this->git->currentBranch() ?? $this->fallbackName;
    }

    protected function getTime(): string
    {
        return date('Y_m_d_His_');
    }
}