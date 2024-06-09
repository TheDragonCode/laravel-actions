# Artisan Command

Quite often, when working with operations, it becomes necessary to run one or another console command,
and each time you have to write the following code:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    public function __invoke(): void
    {
        $this->artisan('some_command', [
            // parameters
        ]);
    }
};
```
