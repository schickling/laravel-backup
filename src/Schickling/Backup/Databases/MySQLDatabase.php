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
	protected $port;

	public function __construct(Console $console, $database, $user, $password, $host, $port)
	{
		$this->console = $console;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->host = $host;
		$this->port = $port;
	}

	public function dump($destinationFile, $compress = false)
	{
		$command = ($compress) ? 
			sprintf('%smysqldump --user=%s --password=%s --host=%s --port=%s %s | gzip -v -9 > %s',
				$this->getDumpCommandPath(),
				escapeshellarg($this->user),
				escapeshellarg($this->password),
				escapeshellarg($this->host),
				escapeshellarg($this->port),
				escapeshellarg($this->database),
				escapeshellarg($destinationFile)
			) :		
			sprintf('%smysqldump --user=%s --password=%s --host=%s --port=%s %s | gzip -v -9 > %s',
				$this->getDumpCommandPath(),
				escapeshellarg($this->user),
				escapeshellarg($this->password),
				escapeshellarg($this->host),
				escapeshellarg($this->port),
				escapeshellarg($this->database),
				escapeshellarg($destinationFile)
			);

		return $this->console->run($command);
	}

	public function restore($sourceFile, $uncompress = false)
	{
		$command = ($uncompress) ? 
			sprintf('%sgunzip -c %s | %smysql --user=%s --password=%s --host=%s --port=%s %s',
				$this->getRestoreCommandPath(),
				escapeshellarg($sourceFile),
				$this->getRestoreCommandPath(),
				escapeshellarg($this->user),
				escapeshellarg($this->password),
				escapeshellarg($this->host),
				escapeshellarg($this->port),
				escapeshellarg($this->database)
			) :
			sprintf('%smysql --user=%s --password=%s --host=%s --port=%s %s < %s',
				$this->getRestoreCommandPath(),
				escapeshellarg($this->user),
				escapeshellarg($this->password),
				escapeshellarg($this->host),
				escapeshellarg($this->port),
				escapeshellarg($this->database),
				escapeshellarg($sourceFile)
			);
			return $this->console->run($command);
	}

	public function getFileExtension($compress = false)
	{
		return ($compress) ? 'sql.gz' : 'sql';
	}
	
	public function getCompressOption()
	{
		return Config::get('backup::mysql.compress');
	}

	protected function getDumpCommandPath()
	{
		return Config::get('backup::mysql.dump_command_path');
	}

	protected function getRestoreCommandPath()
	{
		return Config::get('backup::mysql.restore_command_path');
	}
	
	
}