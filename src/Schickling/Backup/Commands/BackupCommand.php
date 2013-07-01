<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Schickling\Backup\Databases\DatabaseInterface;

class BackupCommand extends Command
{
	protected $database;

	protected $name = 'db:backup';

	protected $description = 'Backup the default database to `app/storage/dumps`';

	public function __construct(DatabaseInterface $database)
	{
		parent::__construct();

		$this->database = $database;
	}

	public function fire()
	{
		$this->checkDumpFolder();
		
		$fileName = date('YmdHis') . '.' .$this->database->getFileExtension();
		$destinationFile = storage_path() . '/dumps\/' . $fileName;

		if ($this->database->dump($destinationFile))
		{
			$this->line(sprintf('Database backup was successful. %s was saved in storage/dumps.', $fileName));
		}
		else
		{
			$this->line('Database backup failed');
		}
	}

	protected function checkDumpFolder()
	{
		$dumpsFolder = storage_path() . '/dumps';

		if ( ! is_dir($dumpsFolder))
		{
			mkdir($dumpsFolder);
		}
	}

}