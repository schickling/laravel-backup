<?php

use Schickling\Backup\Commands\RestoreCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class RestoreCommandTest extends TestCase
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

    public function testSuccessfulRestore()
    {
        $databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');
        $testDumpFile = storage_path() . '/dumps/testDump.sql';

        $databaseMock->shouldReceive('restore')
                     ->with($testDumpFile)
                     ->once()
                     ->andReturn(true);

        $command = new RestoreCommand($databaseMock);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'dump' => 'testDump.sql'
            ));

        $this->assertEquals("testDump.sql was successfully restored.\n", $tester->getDisplay());
    }

    public function testFailingRestore()
    {
        $databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');
        $testDumpFile = storage_path() . '/dumps/testDump.sql';

        $databaseMock->shouldReceive('restore')
                     ->with($testDumpFile)
                     ->once()
                     ->andReturn(false);

        $command = new RestoreCommand($databaseMock);

        $tester = new CommandTester($command);
        $tester->execute(array(
            'dump' => 'testDump.sql'
            ));

        $this->assertEquals("Database restore failed\n", $tester->getDisplay());
    }
}
