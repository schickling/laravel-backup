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

        $this->app->config->set('backup::compress', false);

        $this->databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');
        $this->databaseBuilderMock = m::mock('Schickling\Backup\DatabaseBuilder');
        $this->databaseBuilderMock->shouldReceive('getDatabase')
                           ->once()
                           ->andReturn($this->databaseMock);

        $command = new BackupCommand($this->databaseBuilderMock);

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

    public function testSuccessfulUncompressedBackup()
    {
        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array());

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*Database backup was successful. [0-9]{14}.sql was saved in the dumps folder.(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
    }

    public function testSuccessfulCompressedBackup()
    {
        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->app->config->set('backup::compress', true);
        $this->tester->execute(array());

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*Database backup was successful. [0-9]{14}.sql.gz was saved in the dumps folder.(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
    }

    public function testFailingBackup()
    {
        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn('Error message');

        $this->tester->execute(array());

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*Database backup failed. Error message(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
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

        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array(
            '--upload-s3' => 'bucket-title'
        ));
    }

    public function testKeepOnlyS3()
    {
        $s3Mock = m::mock();
        $s3Mock->shouldReceive('putObject')
               ->andReturn(true);

        AWS::shouldReceive('get')
           ->once()
           ->with('s3')
           ->andReturn($s3Mock);

        File::shouldReceive('delete')
              ->once()
              ->andReturn(true);

        $this->databaseMock->shouldReceive('getFileExtension')
                           ->once()
                           ->andReturn('sql');

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array(
            '--upload-s3' => 'bucket-title',
            '--keep-only-s3' => true
        ));
    }

    public function testAbsolutePathAsFilename()
    {

        $this->databaseMock->shouldReceive('getFileExtension')
                           ->never();

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $filename = '/home/dummy/mydump.sql';

        $this->tester->execute(array(
            'filename' => $filename
        ));

        $regex = "/^(\\033\[[0-9;]*m)*(\\n)*Database backup was successful. Saved to \/home\/dummy\/mydump.sql(\\n)*(\\033\[0m)*$/";
        $this->assertRegExp($regex, $this->tester->getDisplay());
    }

    public function testRelativePathAsFilename()
    {

        $this->databaseMock->shouldReceive('getFileExtension')
                           ->never();

        $this->databaseMock->shouldReceive('dump')
                           ->once()
                           ->andReturn(true);

        $filename = 'dummy/mydump.sql';

        $this->tester->execute(array(
            'filename' => $filename
        ));

        $path = str_replace('/', '\/', getcwd());
        $regex = "/^(\\033\[[0-9;]*m)*(\\n)*Database backup was successful. Saved to " . $path . "\/dummy\/mydump.sql(\\n)*(\\033\[0m)*$/";
        $this->assertRegExp($regex, $this->tester->getDisplay());
    }
}