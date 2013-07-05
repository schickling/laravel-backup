<?php namespace Schickling\Backup;

class DatabaseBuilder
{
	protected $database;
	protected $console;

	public function __construct(array $config)
	{
		$this->console = new Console();

		$default = $config['default'];
		$realConfig = $config['connections'][$default];

		switch ($realConfig['driver'])
		{
			case 'mysql':
			$this->buildMySQL($realConfig);
			break;

			case 'sqlite':
			$this->buildSqlite($realConfig);
			break;

			case 'pgsql':
			$this->buildPostgres($realConfig);
			break;

			default:
			throw new \Exception('Database driver not supported yet');
			break;
		}
	}

	public function getDatabase()
	{
		return $this->database;
	}

	protected function buildMySQL(array $config)
	{
		$this->database = new Databases\MySQLDatabase(
			$this->console,
			$config['database'],
			$config['username'],
			$config['password'],
			$config['host']
			);
	}

	protected function buildSqlite(array $config)
	{
		$this->database = new Databases\SqliteDatabase(
			$this->console,
			$config['database']
			);
	}

	protected function buildPostgres(array $config)
	{
		$this->database = new Databases\PostgresDatabase(
			$this->console,
			$config['database'],
			$config['username'],
			$config['password'],
			$config['host']
			);
	}

}
