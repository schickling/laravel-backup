<?php namespace Schickling\Backup;

use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('schickling/backup');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$databaseBuilder = new DatabaseBuilder();

		$this->app['db.backup'] = $this->app->share(function($app) use ($databaseBuilder)
		{
			return new Commands\BackupCommand($databaseBuilder);
		});

		$this->app['db.restore'] = $this->app->share(function($app) use ($databaseBuilder)
		{
			return new Commands\RestoreCommand($databaseBuilder);
		});

		$this->commands(
			'db.backup',
			'db.restore'
		);
	}
}