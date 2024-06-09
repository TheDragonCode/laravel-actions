# Database Dumper

As you build your application, you may accumulate more and more migrations over time.
This can lead to your `database/migrations` directory becoming bloated with potentially hundreds of migrations.
If you would like, you may "squash" your migrations into a single SQL file.
To get started, execute the `schema:dump` command:

```bash
php artisan schema:dump
```

You can read more about the operation of this console command in
the [official documentation](https://laravel.com/docs/11.x/migrations#squashing-migrations).

Here we mention this console command because operations tend to save the execution state in order to prevent re-runs
where this is not explicitly allowed.
But if you run sequentially the console commands `php artisan schema:dump` and `php artisan migrate:fresh`, you will see
that all actions will be called again.

This is due to the fact that the dump mechanism saves the contents of just one table - `migrations`.

To solve this problem, there is a [Database Data Dumper](https://github.com/TheDragonCode/laravel-data-dumper)
project that allows you to specify a list of tables required for export to a dump.

In addition to those that you can easily specify in its configuration file, we recommend that you also specify
the `operations` table from this project in order to save the state of the operations when performing a clean deployment
of the database from a dump.

```bash
composer require dragon-code/laravel-data-dumper --dev
```
