<?php namespace Schickling\Backup;

use Symfony\Component\Process\Process;
use Schickling\Backup\ConsoleColors;

class Console
{
	public function run($command)
	{
		// Create new Colors class
		$colors = new ConsoleColors();

		$process = new Process($command);
		$process->run();
		echo $colors->getColoredString("\n"."Executed command : ".$command, "brown")."\n";
		if ($process->isSuccessful()) {
			return true;
		} else {
			return $process->getErrorOutput();
		}
	}
}