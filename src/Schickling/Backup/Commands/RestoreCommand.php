<?php namespace Schickling\Backup\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class RestoreCommand extends BaseCommand
{
	protected $name = 'db:restore';

	protected $description = 'Restore a dump from `app/storage/dumps`';

	protected $database;

	public function fire()
	{		
		$this->database = $this->getDatabase($this->input->getOption('database'));

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

		$status = $this->database->restore($sourceFile);
		
		if ($status === true)
		{
			$this->line(sprintf($this->colors->getColoredString("\n".'%s was successfully restored.'."\n",'green'), $fileName));
		}
		else
		{
			$this->line($this->colors->getColoredString("\n".'Database restore failed.'."\n",'red'));
		}
	}

	protected function listAllDumps()
	{
		$finder = new Finder();
		$finder->files()->in($this->getDumpsPath());

		if ($finder->count() > 0)
		{
			$this->line($this->colors->getColoredString("\n".'Please select one of the following dumps:'."\n",'white'));

			$finder->sortByName();
			$count = count($finder);
			$i=0;
			foreach ($finder as $dump)
			{
				$i++;
				if($i!=$count){
					$this->line($this->colors->getColoredString($dump->getFilename(),'brown'));
				}else{
					$this->line($this->colors->getColoredString($dump->getFilename()."\n",'brown'));
				}
			}
		}
		else
		{
			$this->line($this->colors->getColoredString("\n".'You haven\'t saved any dumps.'."\n",'brown'));
		}
	}

	protected function getArguments()
	{
		return array(
			array('dump', InputArgument::OPTIONAL, 'Filename of the dump')
			);
	}

	protected function getOptions()
	{
		return array(
			array('database', null, InputOption::VALUE_OPTIONAL, 'The database connection to restore to'),
		);
	}

}
