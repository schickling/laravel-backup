<?php namespace Schickling\Backup;

use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$databaseBuilder = new DatabaseBuilder($this->app->config['database']);
		$database = $databaseBuilder->getDatabase();

		$this->app['db.backup'] = $this->app->share(function($app) use ($database)
		{
			return new Commands\BackupCommand($database);
		});

		$this->commands(
			'db.backup'
			);
	}

}