# Rolling Back Operations

To roll back the latest operation, you may use the `rollback` command. This command rolls back the last "batch" of operations, which may include multiple operation files:

```
php artisan operations:rollback
```

You may roll back a limited number of operations by providing the `step` option to the rollback command. For example, the following command will roll back the last five operations:

```
php artisan operations:rollback --step=5
```

The `operations:reset` command will roll back all of your application's operations:

```
php artisan operations:reset
```

For example:

```bash
php artisan operations:rollback
# operation                 batch
# 2022_10_12_021837_some    1
# 2022_10_12_021838_some    2
# 2022_10_12_021839_some    2
# 2022_10_12_021840_some    3  // will be canceled
# 2022_10_12_021841_some    3  // will be canceled

php artisan operations:rollback --step=1
# operation                 batch
# 2022_10_12_021837_some    1
# 2022_10_12_021838_some    2
# 2022_10_12_021839_some    2
# 2022_10_12_021840_some    3  // will be canceled
# 2022_10_12_021841_some    3  // will be canceled

php artisan operations:rollback --step=2
# operation                 batch
# 2022_10_12_021837_some    1
# 2022_10_12_021838_some    2  // will be canceled
# 2022_10_12_021839_some    2  // will be canceled
# 2022_10_12_021840_some    3  // will be canceled
# 2022_10_12_021841_some    3  // will be canceled
```

## Roll Back & Operation Using A Single Command

The `operations:refresh` command will roll back all of your operations and then execute the `operations` command. This command effectively re-creates your entire
database:

```
php artisan operations:refresh
```

You may roll back & re-run a limited number of operations by providing the `step` option to the `refresh` command. For example, the following command will roll back &
re-run the last five operations:

```
php artisan operations:refresh --step=5
```

## Drop All & Rerun Operations

The `operations:fresh` command will drop all operation records from the operation table and then execute the operations command:

```
php artisan operations:fresh
```
