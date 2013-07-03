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

#### Restore
Paths are relative to the app/storage/dumps folder.
```
php artisan db:restore dump.sql
```

## TODO - Upcoming Features
* `db:restore` list all available dumps
* `db:restore WRONGFILENAME` more detailed error message
* `db:backup FILENAME` set title for dump
* `db:backup --db CONNECTION` specify connection, default: default connection
* *Some more ideas? Tell me!*
