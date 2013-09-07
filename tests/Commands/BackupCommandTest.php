<?php

use Schickling\Backup\Commands\BackupCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class BackupCommandTest extends TestCase
{
    private $databaseMock;
    private $tester;

    public function setUp()
    {
        parent::setUp();

        $this->databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');
        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $command = new BackupCommand($this->databaseMock);

        $this->tester = new CommandTester($command);
    }

    public function tearDown()
    {
        m::close();
    }

    protected function getPackageProviders()
    {
        return array(
            'Schickling\Backup\BackupServiceProvider',
            'Aws\Laravel\AwsServiceProvider',
        );
    }

    protected function getPackageAliases()
    {
        return array(
            'AWS' => 'Aws\Laravel\AwsFacade',
            );
    }

    public function testSuccessfulBackup()
    {
        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array());

        $this->assertRegExp("/^Database backup was successful. [0-9]{14}.sql was saved in the dumps folder.$/", $this->tester->getDisplay());
    }

    public function testFailingBackup()
    {
        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn('Error message');

        $this->tester->execute(array());

        $this->assertEquals("Database backup failed. Error message\n", $this->tester->getDisplay());
    }

    public function testUploadS3()
    {
        $s3Mock = m::mock();
        $s3Mock->shouldReceive('putObject')
               ->andReturn(true);

        AWS::shouldReceive('get')
           ->once()
           ->with('s3')
           ->andReturn($s3Mock);

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array(
            '--upload-s3' => 'bucket-title'
            ));

        $lines = explode("\n", $this->tester->getDisplay());
        $this->assertRegExp("/^Database backup was successful. [0-9]{14}.sql was saved in the dumps folder.$/", $lines[0]);
        $this->assertEquals("Upload complete.", $lines[1]);

    }
}
