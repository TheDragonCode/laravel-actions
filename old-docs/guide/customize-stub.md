# Customize Stub

You may publish the stub used by the `make:operation` command and/or [Laravel Idea](https://laravel-idea.com)
plugin for [JetBrains PhpStorm](https://www.jetbrains.com/phpstorm/) if you want to modify it.

```bash
php artisan vendor:publish --tag=stubs --provider="DragonCode\LaravelDeployOperations\ServiceProvider"
```

As a result, the file `stubs/deploy-operation.stub` will be created in the root of the project,
which you can change for yourself.
