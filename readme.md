# Laravel Database Schema

## 1. About

This package generate Mysql database schema from migrations files.

## 2. Installation

Install via composer - edit your `composer.json` to require the package.

```js
"require": {
    // ...
    "autn/laravel-schema": "*"
}
```

Then run `composer update` in your terminal to pull it in.
Once this has finished, you will need to add the command to the `commands` array in your `app/Console/Kernel.php` config as follows:

```php
// ....
protected $commands = [
    // ...
    'db:schema' => \Autn\Schema\Console\Commands\DumpSql::class,
];
// ...
```
## 3. Usage

In root Laravel project, type:

```sh
php artisan db:schema
```

The file will generated to the default `databases` path (`database/schema.sql`).

You can change this path by add `--path` option to the command.

Example:

```sh
php artisan db:schema --path=public
```

The default database connect is `mysql`. You can change the connect by add `--dbconnect` to the command.

Example:

```sh
php artisan db:schema --path=public --dbconnect=mysql2
```

**Notice:** If you add `--dbconnect` option, you must remove or rename `.env` file, and you must add config to `config/database.php`.

Example:

```php
// ....
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'db'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
],

'mysql2' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'db2'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', 'root'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
],
// ...
```
