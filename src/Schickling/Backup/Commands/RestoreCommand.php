<?php namespace Schickling\Backup\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class RestoreCommand extends BaseCommand
{
	protected $name = 'db:restore';

	protected $description = 'Restore a dump from `app/storage/dumps`';

	public function fire()
	{		
		$fileName = $this->argument('dump');
		
		if ($fileName)
		{
			$this->restoreDump($fileName);
		}
		else
		{
			$this->listAllDumps();
		}
	}

	protected function restoreDump($fileName)
	{
		$sourceFile = $this->getDumpsPath() . $fileName;

		if ($this->database->restore($sourceFile))
		{
			$this->line(sprintf('%s was successfully restored.', $fileName));
		}
		else
		{
			$this->line('Database restore failed.');
		}
	}

	protected function listAllDumps()
	{
		$finder = new Finder();
		$finder->files()->in($this->getDumpsPath());

		if ($finder->count() > 0)
		{
			$this->line('Please select one of the following dumps:');

			$finder->sortByName();

			foreach ($finder as $dump)
			{
				$this->line($dump->getFilename());
			}
		}
		else
		{
			$this->line('You haven\'t saved any dumps.');
		}
	}

	protected function getArguments()
	{
		return array(
			array('dump', InputArgument::OPTIONAL, 'Filename of the dump')
			);
	}

}