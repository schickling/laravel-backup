<?php namespace Schickling\Backup\Databases;

use Schickling\Backup\Console;
use Config;

class PostgresDatabase implements DatabaseInterface
{

	protected $console;
	protected $database;
	protected $user;
	protected $password;
	protected $host;

	public function __construct(Console $console, $database, $user, $password, $host)
	{
		$this->console = $console;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
	}

	public function dump($destinationFile)
	{
		$excludedTablesStr = implode(' ', array_map(function ($table) {
			return '--exclude-table-data=' . escapeshellarg($table);
		}, $this->getExcludedTables()));
		$command = sprintf('PGPASSWORD=%s pg_dump -Fc --no-acl --no-owner %s -h %s -U %s %s > %s',
			escapeshellarg($this->password),
			$excludedTablesStr,
			escapeshellarg($this->host),
			escapeshellarg($this->user),
			escapeshellarg($this->database),
			escapeshellarg($destinationFile)
		);

		return $this->console->run($command);
	}

	public function restore($sourceFile)
	{
		$command = sprintf('PGPASSWORD=%s pg_restore --verbose --clean --no-acl --no-owner -h %s -U %s -d %s %s',
			escapeshellarg($this->password),
			escapeshellarg($this->host),
			escapeshellarg($this->user),
			escapeshellarg($this->database),
			escapeshellarg($sourceFile)
		);

		return $this->console->run($command);
	}

	public function getFileExtension()
	{
		return 'dump';
	}

	protected function getExcludedTables()
	{
		return Config::get('backup::postgres.exclude_table_data', array());
	}
}
