<?php namespace Schickling\Backup\Databases;

use Schickling\Backup\Console;
use Config;

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
		$command = sprintf('%smysqldump --user=%s --password=%s --host=%s %s > %s',
			$this->getDumpCommandPath(),
			escapeshellarg($this->user),
			escapeshellarg($this->password),
			escapeshellarg($this->host),
			escapeshellarg($this->database),
			escapeshellarg($destinationFile)
		);

		return $this->console->run($command);
	}

	public function restore($sourceFile)
	{
		$command = sprintf('%smysql --user=%s --password=%s --host=%s %s < %s',
			$this->getRestoreCommandPath(),
			escapeshellarg($this->user),
			escapeshellarg($this->password),
			escapeshellarg($this->host),
			escapeshellarg($this->database),
			escapeshellarg($sourceFile)
		);

		return $this->console->run($command);
	}

	public function getFileExtension()
	{
		return 'sql';
	}

	protected function getDumpCommandPath()
	{
		$default = '';

		return Config::get('database.backup.mysql.dump_command_path', $default);;
	}

	protected function getRestoreCommandPath()
	{
		$default = '';

		return Config::get('database.backup.mysql.restore_command_path', $default);;
	}

}