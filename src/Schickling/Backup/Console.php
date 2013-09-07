<?php namespace Schickling\Backup;

use Symfony\Component\Process\Process;

class Console
{
	public function run($command)
	{
		$process = new Process($command);
		$process->run();

		if ($process->isSuccessful()) {
			return true;
		} else {
			return $process->getErrorOutput();
		}
	}
}