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
		$this->app['db.backup'] = $this->app->share(function($app)
		{
			return new Commands\BackupCommand($this->getDatabase());
		});

		$this->commands(
			'db.backup'
			);
	}

	protected function getDatabase()
	{
		$databaseConfig = $this->app->config['database'];
		if ($databaseConfig['default'] != 'mysql')
		{
			throw new \Exception('Database driver not supported yet');
		}


		$databaseConfig = $databaseConfig['connections'][$databaseConfig['default']];
		$console = new Console();
		$database = new Databases\MySQLDatabase(
			$console,
			$databaseConfig['database'],
			$databaseConfig['username'],
			$databaseConfig['password'],
			$databaseConfig['host']
			);

		return $database;
	}

}