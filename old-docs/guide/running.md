# Running Operations

To run all of your outstanding operations, execute the `operations` artisan command:

```bash
php artisan operations
```

The order in which operations are called is checked by file name in alphabetical order,
without taking into account directory names:

```bash
# actual file names
2022_10_14_000001_test1      # 1
2022_10_14_000004_test4      # 4
bar/2022_10_14_000003_test3  # 3
foo/2022_10_14_000002_test2  # 2
```

```bash
# order of running operations at startup
2022_10_14_000001_test1      # 1
foo/2022_10_14_000002_test2  # 2
bar/2022_10_14_000003_test3  # 3
2022_10_14_000004_test4      # 4
```

## Isolating Operations Execution

If you are deploying your application across multiple servers and running operations as part of your deployment process,
you likely do not want two servers attempting to run
the database at the same time. To avoid this, you may use the `isolated` option when invoking the `operations` command.

When the `isolated` option is provided, Laravel will acquire an atomic lock using your application's cache driver before
attempting to run your operations. All other attempts to
run the `operations` command while that lock is held will not execute; however, the command will still exit with a
successful exit status code:

```bash
php artisan operations --isolated
```

## Split Launch Option

Sometimes it becomes necessary to launch operations separately, for example, to notify about the successful deployment
of a project.

There is a `before` option for this when calling operations:

```bash
php artisan operations --before
```

When calling the `operations` command with the `before` parameter, the script will execute only those operations within
which the value of the `before` parameter is `true`.

For backwards compatibility, the `before` parameter is set to `true` by default, but operations will only be executed if
the option is explicitly passed.

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected bool $before = false;

    public function __invoke(): void
    {
        // some code
    }
};
```

For example, you need to call operations when deploying an application. Some operations should be run after the
operations are deployed, and others after the application is fully
launched.

To run, you need to pass the `before` parameter. For example, when
using [`deployer`](https://github.com/deployphp/deployer) it would look like this:

```php
task('deploy', [
    // ...
    'artisan:migrate',
    'artisan:operation --before', // here
    'deploy:publish',
    'php-fpm:reload',
    'artisan:queue:restart',
    'artisan:operations', // here
]);
```

Thus, when `operations` is called, all operations whose `before` parameter is `true` will be executed, and after that,
the remaining tasks will be executed.

> Note:
> If you call the `operations` command without the `before` parameter,
> then all tasks will be executed regardless of the value of the `$before`
> attribute inside the operation class.

## Forcing Operations To Run In Production

> Some commands cannot be executed in production without confirmation.
> These include all commands except `operations:status` and `operations`.

Some operations are destructive, which means they may cause you to lose data. In order to protect you from running these
commands against your production database,
you will be prompted for confirmation before the commands are executed. To force the commands to run without a prompt,
use the `--force` flag:

```bash
php artisan operations:install --force
```

## Execution Every Time

In some cases, you need to call the code every time you deploy the application. For example, to call reindexing.

To do this, override the `$once` variable in the operation file:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected bool $once = false;

    public function __invoke(): void
    {
        // some code
    }
};
```

If the value is `$once = false`, the `up` method will be called every time the `operations` command called.

In this case, information about it will not be written to the `operations` table and, therefore, the `down` method will
not be called when the rollback command is called.

> Note
>
> When using the `before` parameter to run command, it is recommended to override the value of the `$before` attribute
> to `false`, otherwise this operation will be executed twice.

## Execution In A Specific Environment

In some cases, it becomes necessary to execute an operation in a specific environment. For example `production`.

For this you can use the `$environment` parameter:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected string|array|null $environment = 'production';

    public function __invoke(): void
    {
        // some code
    }
};
```

You can also specify multiple environment names:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected string|array|null $environment = ['testing', 'staging'];

    public function __invoke(): void
    {
        // some code
    }
};
```

By default, the operation will run in all environments. The same will happen if you specify `null` or `[]` as the value.

## Execution Excluding Certain Environments

In some cases, it becomes necessary to execute an operation excluding certain environments. For example `production`.

For this you can use the `$except_environment` parameter:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected string|array|null $exceptEnvironment = 'production';

    public function __invoke(): void
    {
        // some code
    }
};
```

You can also specify multiple environment names:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    protected string|array|null $exceptEnvironment = ['testing', 'staging'];

    public function __invoke(): void
    {
        // some code
    }
};
```

By default, no operations will be excluded. The same happens if you specify `null` or `[]` value.

## Database Transactions

In some cases, it becomes necessary to undo previously performed operations in the database. For example, when code
execution throws an error. To do this, the code must be wrapped in
a transaction.

By setting the `$transactions = true` parameter, you will ensure that your code is wrapped in a transaction without
having to manually call the `DB::transaction()` method. This
will reduce the time it takes to create the operation.

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    public function __invoke(): void
    {
        // some code
    }

    public function hasTransactions(): bool
    {
        return true;
    }

    public function transactionAttempts(): int
    {
        return 4;
    }
};
```

## Asynchronous Call

In some cases, it becomes necessary to execute operations in an asynchronous manner without delaying the deployment
process.

To do this, you need to override the `async` method in the operation class:

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    public function __invoke(): void
    {
        // some code
    }

    public function isAsync(): bool
    {
        return true;
    }
};
```

In this case, the operation file that defines this parameter will run asynchronously using
the `DragonCode\LaravelDeployOperations\Jobs\OperationJob` class.

The name of the connection and queue can be changed through
the [settings](https://github.com/TheDragonCode/laravel-deploy-operations/tree/main/config).

::: Info
We remind you that in this case the [queuing system](https://laravel.com/docs/queues) must work in your application.

Using Laravel version 8.37 and above, checking for the [uniqueness](https://laravel.com/docs/10.x/queues#unique-jobs) of
the execution is supported.
:::
