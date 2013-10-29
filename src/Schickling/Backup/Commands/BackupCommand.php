<?php namespace Schickling\Backup\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use AWS;
use Config;

class BackupCommand extends BaseCommand
{
	protected $name = 'db:backup';
	protected $description = 'Backup the default database to `app/storage/dumps`';
	protected $filePath;
	protected $fileName;

	public function fire()
	{
		$this->checkDumpFolder();

		if ($this->argument('filename'))
		{
			// Is it an absolute path?
			if (substr($this->argument('filename'), 0, 1) == '/')
			{
				$this->filePath = $this->argument('filename');
				$this->fileName = basename($this->filePath);
			}
			// It's relative path?
			else
			{
				$this->filePath = getcwd() . '/' . $this->argument('filename');
				$this->fileName = basename($this->filePath);
			}
		}
		else
		{
			$this->fileName = date('YmdHis') . '.' .$this->database->getFileExtension();
			$this->filePath = rtrim($this->getDumpsPath(), '/') . '/' . $this->fileName;
		}

		$status = $this->database->dump($this->filePath);

		if ($status === true)
		{
			if ($this->argument('filename'))
			{
				$this->line(sprintf($this->colors->getColoredString("\n".'Database backup was successful. Saved to %s'."\n",'green'), $this->filePath));
			}
			else
			{
				$this->line(sprintf($this->colors->getColoredString("\n".'Database backup was successful. %s was saved in the dumps folder.'."\n",'green'), $this->fileName));
			}

			if ($this->option('upload-s3'))
			{
				$this->uploadS3();
				$this->line($this->colors->getColoredString("\n".'Upload complete.'."\n",'green'));
			}
		}
		else
		{
			$this->line(sprintf($this->colors->getColoredString("\n".'Database backup failed. %s'."\n",'red'), $status));
		}
	}

	/**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('filename', InputArgument::OPTIONAL, 'Filename or -path for the dump.'),
        );
    }

	protected function getOptions()
	{
		return array(
			array('upload-s3', 'u', InputOption::VALUE_REQUIRED, 'Upload the dump to your S3 bucket')
			);
	}

	protected function checkDumpFolder()
	{
		$dumpsPath = $this->getDumpsPath();

		if ( ! is_dir($dumpsPath))
		{
			mkdir($dumpsPath);
		}
	}

	protected function uploadS3()
	{
		$bucket = $this->option('upload-s3');
		$s3 = AWS::get('s3');
		$s3->putObject(array(
			'Bucket'     => $bucket,
			'Key'        => $this->getS3DumpsPath() . '/' . $this->fileName,
			'SourceFile' => $this->filePath,
			));
	}

	protected function getS3DumpsPath()
	{
		$default = 'dumps';

		return Config::get('database.backup.s3.path', $default);;
	}

}