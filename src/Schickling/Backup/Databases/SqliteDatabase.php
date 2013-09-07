<?php namespace Schickling\Backup\Databases;

use Schickling\Backup\Console;

class SqliteDatabase implements DatabaseInterface
{

	protected $console;
	protected $databaseFile;

	public function __construct(Console $console, $databaseFile)
	{
		$this->console = $console;
		$this->databaseFile = $databaseFile;
	}

	public function dump($destinationFile)
	{
		$command = sprintf('cp %s %s',
			escapeshellarg($this->databaseFile),
			escapeshellarg($destinationFile)
		);

		return $this->console->run($command);
	}

	public function restore($sourceFile)
	{
		$command = sprintf('cp -f %s %s',
			escapeshellarg($sourceFile),
			escapeshellarg($this->databaseFile)
		);

		return $this->console->run($command);
	}

	public function getFileExtension()
	{
		return 'sqlite';
	}
}