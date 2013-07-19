<?php

use Schickling\Backup\Commands\RestoreCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class RestoreCommandTest extends TestCase
{
    private $databaseMock;
    private $tester;

    public function setUp()
    {
        parent::setUp();

        $this->databaseMock = m::mock('Schickling\Backup\Databases\DatabaseInterface');

        $command = new RestoreCommand($this->databaseMock);

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
        );
    }

    public function testSuccessfulRestore()
    {
        $testDumpFile = storage_path() . '/dumps/testDump.sql';

        $this->databaseMock->shouldReceive('restore')
                           ->with($testDumpFile)
                           ->once()
                           ->andReturn(true);

        $this->tester->execute(array(
            'dump' => 'testDump.sql'
            ));

        $this->assertEquals("testDump.sql was successfully restored.\n", $this->tester->getDisplay());
    }

    public function testFailingRestore()
    {
        $testDumpFile = storage_path() . '/dumps/testDump.sql';

        $this->databaseMock->shouldReceive('restore')
                           ->with($testDumpFile)
                           ->once()
                           ->andReturn(false);

        $this->tester->execute(array(
            'dump' => 'testDump.sql'
            ));

        $this->assertEquals("Database restore failed.\n", $this->tester->getDisplay());
    }

    public function testDumpListForEmptyFolder()
    {
        $this->app->config->set('database.dumps', __DIR__ . '/resources/EmptyFolder');

        $this->tester->execute(array());

        $this->assertEquals("You haven't saved any dumps.\n", $this->tester->getDisplay());
    }

    public function testDumpListForNonEmptyFolder()
    {
        $this->app->config->set('database.dumps', __DIR__ . '/resources/NonEmptyFolder');

        $this->tester->execute(array());

        $this->assertEquals("Please select one of the following dumps:\nhello.sql\nworld.sql\n", $this->tester->getDisplay());
    }
}
