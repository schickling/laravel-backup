<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Config;
use Schickling\Backup\Databases\DatabaseInterface;

class BaseCommand extends Command
{
	protected $database;

	public function __construct(DatabaseInterface $database)
	{
		parent::__construct();

		$this->database = $database;
	}

	protected function getDumpsPath()
	{
		$default = sprintf('%s/dumps/', storage_path());

		return Config::get('database.dumps', $default);;
	}

}