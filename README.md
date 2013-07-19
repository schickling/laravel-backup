laravel-backup [![Build Status](https://travis-ci.org/schickling/laravel-backup.png)](https://travis-ci.org/schickling/laravel-backup)
==============

Backup and restore database support for Laravel 4 applications

## Installation

1. Add the following to your composer.json and run `composer update`

    ```json
    {
        "require": {
            "schickling/backup": "dev-master"
        }
    }
    ```

2. Add `Schickling\Backup\BackupServiceProvider` to your config/app.php

## Usage

#### Backup
Creates a dump file in `app/storage/dumps`
```
php artisan db:backup
```

##### Upload to AWS S3
```
php artisan db:backup --upload-s3 your-bucket
```

#### Restore
Paths are relative to the app/storage/dumps folder.

##### Restore a dump
```
php artisan db:restore dump.sql # restore dump.sql
```

##### List dumps
```
php artisan db:restore
```

## Configuration
You can configure the package by adding a `backup` section in your `app/config/database.php`
```php
    // ...

    'default' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Backup settings
    |--------------------------------------------------------------------------
    |
    */
    'backup' => array(
        'path'  => 'your/local/dump/folder',
        's3'    => array(
            'path'  => 'your/s3/dump/folder'
            )
        )

    // ...
```

## TODO - Upcoming Features
* `db:restore WRONGFILENAME` more detailed error message
* `db:backup FILENAME` set title for dump
* `db:backup --db CONNECTION` specify connection, default: default connection
* Compress dump files
* More detailed folder checking (permission, existence, ...)
* *Some more ideas? Tell me!*
