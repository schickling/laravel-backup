<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Config;
use Schickling\Backup\DatabaseBuilder;
use Schickling\Backup\ConsoleColors;

class BaseCommand extends Command
{
	protected $databaseBuilder;
	protected $colors;

	public function __construct(DatabaseBuilder $databaseBuilder)
	{
		parent::__construct();
		$this->databaseBuilder = $databaseBuilder;
		$this->colors = new ConsoleColors();
	}

	public function getDatabase($database)
	{
		$database = $database ?: Config::get('database.default');
		$realConfig = Config::get("database.connections.$database");
		return $this->databaseBuilder->getDatabase($realConfig);
	}

	protected function getDumpsPath()
	{
		$default = sprintf('%s/dumps/', storage_path());

		return Config::get('database.backup.path', $default);;
	}

}
