<?php

use Schickling\Backup\Commands\BackupCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class BackupCommandTest extends TestCase
{

    public function tearDown()
    {
        m::close();
    }

    protected function getPackageProviders()
    {
        return array(
            'Schickling\Backup\BackupServiceProvider',
        );
    }

    public function testSuccessfulBackup()
    {
        $databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');

        $databaseMock->shouldReceive('dump')
                     ->once()
                     ->andReturn(true);

        $databaseMock->shouldReceive('getFileExtension')
                     ->once()
                     ->andReturn('sql');

        $command = new BackupCommand($databaseMock);

        $tester = new CommandTester($command);
        $tester->execute(array());

        $this->assertRegExp("/^Database backup was successful. [0-9]{14}.sql was saved in the dumps folder.$/", $tester->getDisplay());
    }

    public function testFailingBackup()
    {
        $databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');

        $databaseMock->shouldReceive('dump')
                     ->once()
                     ->andReturn(false);

        $databaseMock->shouldReceive('getFileExtension')
                     ->once()
                     ->andReturn('sql');

        $command = new BackupCommand($databaseMock);

        $tester = new CommandTester($command);
        $tester->execute(array());

        $this->assertEquals("Database backup failed\n", $tester->getDisplay());
    }
}
