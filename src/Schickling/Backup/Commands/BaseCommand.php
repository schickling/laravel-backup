<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
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
		return sprintf('%s/dumps/', storage_path());
	}

}