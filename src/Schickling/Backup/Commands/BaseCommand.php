<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Config;
use Schickling\Backup\Databases\DatabaseInterface;
use Schickling\Backup\ConsoleColors;
use Symfony\Component\Filesystem\Filesystem;

class BaseCommand extends Command
{
	protected $database;
	protected $colors;
	protected $fs;

	public function __construct(DatabaseInterface $database)
	{
		parent::__construct();
		$this->colors = new ConsoleColors();
		$this->database = $database;
		$this->fs = new Filesystem();
	}

	protected function getDumpsPath()
	{
		$default = sprintf('%s/dumps/', storage_path());

		return Config::get('database.backup.path', $default);;
	}

}