<?php namespace Schickling\Backup\Commands;

use Illuminate\Console\Command;
use Config;
use Schickling\Backup\DatabaseBuilder;
use Schickling\Backup\ConsoleColors;
use Schickling\Backup\Console;

class BaseCommand extends Command
{
	protected $databaseBuilder;
	protected $colors;
	protected $console;

	public function __construct(DatabaseBuilder $databaseBuilder)
	{
		parent::__construct();

		$this->databaseBuilder = $databaseBuilder;
		$this->colors = new ConsoleColors();
		$this->console = new Console();
	}

	public function getDatabase($database)
	{
		$database = $database ? : Config::get('database.default');
		$realConfig = Config::get('database.connections.' . $database);

		return $this->databaseBuilder->getDatabase($realConfig);
	}

	protected function getDumpsPath()
	{
		return Config::get('backup::path');
	}

	public function enableCompression()
	{
		return Config::set('backup::compress', true);
	}

	public function disableCompression()
	{
		return Config::set('backup::compress', false);
	}

	public function isCompressionEnabled()
	{
		return Config::get('backup::compress');
	}

	public function isCompressed($fileName)
	{
		return pathinfo($fileName, PATHINFO_EXTENSION) === "gz";
	}
}
