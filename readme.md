# Laravel Database Schema

[![Latest Version on Packagist](https://img.shields.io/packagist/v/autn/laravel-schema.svg?style=flat-square)](https://packagist.org/packages/autn/laravel-schema)
[![Build Status](https://api.travis-ci.org/autn/laravel-schema.svg?branch=master&style=flat-square)](https://travis-ci.org/autn/laravel-schema)
[![Total Downloads](https://img.shields.io/packagist/dt/autn/laravel-schema.svg?style=flat-square)](https://packagist.org/packages/autn/laravel-schema)

## 1. About

This package generate Mysql database schema from migrations files.

Update Sep 2020: Now support latest Laravel version

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

**Notice:** The command will refresh your database, the seeding and actual datas will remove. I recommend use `--dbconnect` to run with other database.

In root Laravel project, type:

```sh
php artisan db:schema
```

The file will generate to the default `databases` path (`database/schema.sql`).

You can change this path by add `--path` option to the command.

Example:

```sh
php artisan db:schema --path=public
```

The default database connect is `mysql` in `config/database.php`. You can change the connect by add `--dbconnect` to the command.

Example:

```sh
php artisan db:schema --path=public --dbconnect=mysql2
```

**Notice:** If you add `--dbconnect` option, you must add config to `config/database.php`.

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
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'db2',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'strict' => false,
    'engine' => null,
],
// ...
```

This is full options:
```
--path=: Path to save schema file

--dbconnect=: Database connect to run

--force: Run without confirmation

--method=: Name of method (mysqldump/php). If your server not install mysql (remote to other database server), you must select `php` method

--refresh=: Public migration files and refresh migrations (yes/no)

--type=: Type of file (sql/gzip/bzip2)
```

## 4. Changelog

- Version 1.x
```
Supported mysqldump method
```

- Version 2.0.x
```
Supported php method (mysqldump version php)
```

- Version 2.1.x
```
Supported compress (type option)
```

- Version 3.x
```
Update to support latest Laravel version
```
