# Installation

## Install the package

All you need to do to get started is add `Deploy Operations` to your [composer](https://getcomposer.org) dependencies:

```bash
composer require dragon-code/laravel-deploy-operations
```

If necessary, you can publish the configuration file by calling the console command:
В случае необходимости

```bash
php artisan vendor:publish --tag=config --provider="DragonCode\LaravelDeployOperations\ServiceProvider"
```

## Publish the operation stub (optional)

You may publish the stub used by the `make:operation` command and/or [Laravel Idea](https://laravel-idea.com)
plugin for [JetBrains PhpStorm](https://www.jetbrains.com/phpstorm/) if you want to modify it.

```bash
php artisan vendor:publish --tag=stubs --provider="DragonCode\LaravelDeployOperations\ServiceProvider"
```

As a result, the file `stubs/deploy-operation.stub` will be created in the root of the project,
which you can change for yourself.
