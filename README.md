Backup and restore database support for Laravel 5 applications

## Installation

1. Add the following to your composer.json and run `composer update`

    ```json
    {	
	    "repositories": [
      	{
          "type": "git",
					"url": "https://github.com/aaronhuisinga/laravel-backup.git"
				}
			],
      "require": {
        "schickling/backup": "dev-master"
      }
    }
    ```

2. Add `Schickling\Backup\BackupServiceProvider` to your config/app.php

## Usage

#### Backup
Creates a dump file in `app/storage/dumps`
```sh
$ php artisan db:backup
```

###### Use specific database
```sh
$ php artisan db:backup --database=mysql
```

###### Upload to AWS S3
```sh
$ php artisan db:backup --upload-s3 your-bucket
```
Uses the [aws/aws-sdk-php-laravel](https://github.com/aws/aws-sdk-php-laravel) package which needs to be [configured](https://github.com/aws/aws-sdk-php-laravel#configuration).

#### Restore
Paths are relative to the app/storage/dumps folder.

###### Restore a dump
```sh
$ php artisan db:restore dump.sql
```

###### List dumps
```sh
$ php artisan db:restore
```

## Configuration
Since version `0.5.0` this package follows the recommended standard for configuration. In order to configure this package please run the following command:

```sh
$ php artisan config:publish schickling/backup
```

__All settings are optional and have reasonable default values.__
```php

return array(

	// add a backup folder in the app/database/ or your dump folder
    'path' => app_path() . '/database/backup/',

    // add the path to the restore and backup command of mysql
    // this exemple is if your are using MAMP server on a mac
    // on windows: 'C:\\...\\mysql\\bin\\'
    // on linux: '/usr/bin/'
    // trailing slash is required
    'mysql' => array(
			'dump_command_path' => '/Applications/MAMP/Library/bin/',
			'restore_command_path' => '/Applications/MAMP/Library/bin/',
		),

    // s3 settings
    's3' => array(
        'path'  => 'your/s3/dump/folder'
        )
);
```

## Dependencies

#### ...for MySQL
You need to have `mysqldump` installed. It's usually already installed with MySQL itself.

## TODO - Upcoming Features
* `db:restore WRONGFILENAME` more detailed error message
* `db:backup FILENAME` set title for dump
* Compress dump files
* S3
 * Upload as default
 * default bucket
* More detailed folder checking (permission, existence, ...)
* *Some more ideas? Tell me!*

