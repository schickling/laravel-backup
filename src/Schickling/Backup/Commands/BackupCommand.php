<?php namespace Schickling\Backup\Commands;

class BackupCommand extends BaseCommand
{
	protected $name = 'db:backup';

	protected $description = 'Backup the default database to `app/storage/dumps`';

	public function fire()
	{
		$this->checkDumpFolder();
		
		$fileName = date('YmdHis') . '.' .$this->database->getFileExtension();
		$destinationFile = $this->getDumpsPath() . $fileName;

		if ($this->database->dump($destinationFile))
		{
			$this->line(sprintf('Database backup was successful. %s was saved in the dumps folder.', $fileName));
		}
		else
		{
			$this->line('Database backup failed');
		}
	}

	protected function checkDumpFolder()
	{
		$dumpsPath = $this->getDumpsPath();

		if ( ! is_dir($dumpsPath))
		{
			mkdir($dumpsPath);
		}
	}

}