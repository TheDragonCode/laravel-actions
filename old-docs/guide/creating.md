# Creating Operations

To create an operation use the `make:operation` artisan command:

```bash
php artisan make:operation some_name
```

The new operation's file will be placed in your `operations` directory in the base path of your app.

Each operation file name contains a timestamp, which allows Laravel to determine the order of the operations.


## Automatically Generate A File Name

If you do not specify the "name" attribute, then the file name will be generated automatically according to the rule:

> git branch name ?: 'auto'

```bash
php artisan make:operation

### When the git repository is found (`base_path('.git')` directory is exists) and HEAD branch name is 'qwerty'
# 2022_10_11_225116_qwerty.php
# 2022_10_11_225118_qwerty.php
# 2022_10_11_225227_qwerty.php

### When the git repository is not found (`base_path('.git')` directory doesn't exists).
# 2022_10_11_225116_auto.php
# 2022_10_11_225118_auto.php
# 2022_10_11_225227_auto.php
```

## Nested Files

You can use nested paths to create operations:

```bash
php artisan make:operation Foo/Bar/QweRty
php artisan make:operation Foo/Bar/QweRty.php

php artisan make:operation Foo\Bar\QweRty
php artisan make:operation Foo\Bar\QweRty.php

php artisan make:operation foo\bar\QweRty
php artisan make:operation foo\bar\QweRty.php
```

All of these commands will create a file called `operations/foo/bar/Y_m_d_His_qwe_rty.php`.

For example:

```bash
php artisan make:operation foo\bar\QweRty
# operations/foo/bar/2022_10_11_225734_qwe_rty.php

php artisan make:operation foo\bar\QweRty.php
# operations/foo/bar/2022_10_11_225734_qwe_rty.php

php artisan make:operation foo/bar/QweRty
# operations/foo/bar/2022_10_11_225734_qwe_rty.php

php artisan make:operation foo/bar/QweRty.php
# operations/foo/bar/2022_10_11_225734_qwe_rty.php
```

## Invokable Method

By default, the new operation class will contain the `__invoke` method, but you can easily replace it with public `up` name.

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    public function __invoke(): void
    {
        // some code
    }
};
```

> Note that the `__invoke` method has been added as a single call.
> This means that when the operation is running, it will be called, but not when it is rolled back.
>
> You should also pay attention to the fact that if there is an `__invoke` method in the class, the `down` method will not be called.

```php
use DragonCode\LaravelDeployOperations\Operation;

return new class extends Operation
{
    public function __invoke(): void {} // called when `php artisan operations` running

    public function down(): void {} // doesn't call when `php artisan migrate:rollback` running
                                    // and any other commands to revert the operation.  
};
```

## Dependency Injection

You can also use the dependency injection with `__invoke`, `up` and `down` methods:

```php
use DragonCode\LaravelDeployOperations\Operation;
use Tests\Concerns\Some;

return new class extends Operation
{
    public function __invoke(Some $some): void
    {
        $value = $some->get('qwerty');
    }
};
```

```php
use DragonCode\LaravelDeployOperations\Operation;
use Tests\Concerns\Some;

return new class extends Operation
{
    public function up(Some $some): void
    {
        $value = $some->get('qwerty');
    }

    public function down(Some $some): void
    {
        $value = $some->get('qwerty');
    }
};
```
