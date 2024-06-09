# Execution Status

You can also override the `success` and `failed` methods, which are called on success or failure processing.

## If Success

```php
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facade\Log;

return new class extends Operation
{
    public function up(): void
    {
       //
    }

    public function down(): void
    {
       //
    }

    public function success(): void
    {
       Log::info('success');
    }

    public function failed(): void
    {
       Log::info('failed');
    }
};
```

Call the `php artisan operations` command.

The log file will contain two `success` entries.

## If Failed

```php
use DragonCode\LaravelDeployOperations\Operation;
use Exeption;
use Illuminate\Support\Facade\Log;

return new class extends Operation
{
    public function up(): void
    {
       throw new Exeption();
    }

    public function down(): void
    {
       throw new Exeption();
    }

    public function success(): void
    {
       Log::info('success');
    }

    public function failed(): void
    {
       Log::info('failed');
    }
};
```

Call the `php artisan operations` command.

The log file will contain two `failed` entries.

## Invokable

The methods will work in the same way in conjunction with the `__invoke` magic method.
The only difference is that in this case the `down` method will not be executed.

```php
use DragonCode\LaravelDeployOperations\Operation;
use Illuminate\Support\Facade\Log;

return new class extends Operation
{
    public function __invoke(): void
    {
       //
    }

    public function success(): void
    {
       Log::info('success');
    }

    public function failed(): void
    {
       Log::info('failed');
    }
};
```
