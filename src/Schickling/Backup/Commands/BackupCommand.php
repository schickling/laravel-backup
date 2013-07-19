<?php namespace Schickling\Backup\Commands;

use Symfony\Component\Console\Input\InputOption;
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
		
		$this->fileName = date('YmdHis') . '.' .$this->database->getFileExtension();
		$this->filePath = $this->getDumpsPath() . $this->fileName;

		if ($this->database->dump($this->filePath))
		{
			if ($this->option('upload-s3'))
			{
				$this->uploadS3();
			}

			$this->line(sprintf('Database backup was successful. %s was saved in the dumps folder.', $this->fileName));
		}
		else
		{
			$this->line('Database backup failed');
		}
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
			'Key'        => $this->getS3DumpsPath() . $this->fileName,
			'SourceFile' => $this->filePath,
		));
	}

	protected function getS3DumpsPath()
	{
		$default = 'dumps/';

		return Config::get('database.backup.s3.path', $default);;
	}

}