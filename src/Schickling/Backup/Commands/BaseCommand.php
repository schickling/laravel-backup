<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Config;
use Schickling\Backup\DatabaseBuilder;
use Schickling\Backup\ConsoleColors;
use Symfony\Component\Filesystem\Filesystem;

class BaseCommand extends Command
{
	protected $databaseBuilder;
	protected $colors;
	protected $fs;

	public function __construct(DatabaseBuilder $databaseBuilder)
	{
		parent::__construct();
		$this->colors = new ConsoleColors();
		$this->databaseBuilder = $databaseBuilder;
		$this->fs = new Filesystem();
	}

	public function getDatabase($database)
	{
		$database = $database ? : Config::get('database.default');
		$realConfig = Config::get('database.connections.' . $database);

		return $this->databaseBuilder->getDatabase($realConfig);
	}

	protected function getDumpsPath()
	{
		return Config::get('database.backup.path');
	}

}