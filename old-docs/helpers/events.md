# Events

You can also handle events when executing operations:

```php
DragonCode\LaravelDeployOperations\Events\DeployOperationStarted::class
DragonCode\LaravelDeployOperations\Events\DeployOperationEnded::class
DragonCode\LaravelDeployOperations\Events\DeployOperationFailed::class
DragonCode\LaravelDeployOperations\Events\NoPendingDeployOperations::class
```

If there are no operation files to execute, the `NoPendingDeployOperations` event will be sent.

In other cases, the `DeployOperationStarted` event will be sent before processing starts,
and the `DeployOperationEnded` event will be sent after processing.

For example:

```php
namespace App\Providers;

use App\Listeners\SomeOperationsListener;
use DragonCode\LaravelDeployOperations\Events\DeployOperationEnded;
use DragonCode\LaravelDeployOperations\Events\DeployOperationFailed;
use DragonCode\LaravelDeployOperations\Events\DeployOperationStarted;
use DragonCode\LaravelDeployOperations\Events\NoPendingDeployOperations;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DeployOperationStarted::class    => [SomeOperationsListener::class],
        DeployOperationEnded::class      => [SomeOperationsListener::class],
        DeployOperationFailed::class     => [SomeOperationsListener::class],
        NoPendingDeployOperations::class => [SomeOperationsListener::class],
    ];
}
```

```php
namespace App\Listeners;

use DragonCode\LaravelDeployOperations\Enums\MethodEnum;
use DragonCode\LaravelDeployOperations\Events\BaseEvent;

class SomeOperationsListener
{
    public function handle(BaseEvent $event): void
    {
        $method   = $event->method; // MethodEnum object value
        $isBefore = $event->before; // boolean
    }
}
```
