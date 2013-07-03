<?php namespace Schickling\Backup\Commands;

use Symfony\Component\Console\Input\InputArgument;

class RestoreCommand extends BaseCommand
{
	protected $name = 'db:restore';

	protected $description = 'Restore a dump from `app/storage/dumps`';

	public function fire()
	{		
		$fileName = $this->argument('dump');
		$sourceFile = $this->getDumpsPath() . $fileName;

		if ($this->database->restore($sourceFile))
		{
			$this->line(sprintf('%s was successfully restored.', $fileName));
		}
		else
		{
			$this->line('Database restore failed');
		}
	}

	protected function getArguments()
	{
		return array(
			array('dump', InputArgument::REQUIRED, 'Filename of the dump')
			);
	}

}