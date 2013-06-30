<?php namespace Schickling\Backup\Databases;

use Schickling\Backup\Console;

class MySQLDatabase implements DatabaseInterface
{

	protected $console;
	protected $database;
	protected $user;
	protected $password;
	protected $host;

	public function __construct(Console $console, $database, $user, $password, $host = 'localhost')
	{
		$this->console = $console;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
	}

	public function dump($destinationFile)
	{
		$command = sprintf('mysqldump --user=%s --password=%s --host=%s %s > %s',
			$this->user,
			$this->password,
			$this->host, 
			$this->database,
			$destinationFile
		);

		return $this->console->run($command);
	}

	public function getFileExtension()
	{
		return 'sql';
	}
}