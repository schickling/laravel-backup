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
        $this->databaseBuilderMock = m::mock('Schickling\Backup\DatabaseBuilder');
        $this->databaseBuilderMock->shouldReceive('getDatabase')
                           ->once()
                           ->andReturn($this->databaseMock);

        $command = new RestoreCommand($this->databaseBuilderMock);

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

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*testDump.sql was successfully restored.(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
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

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*Database restore failed.(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
    }

    public function testDumpListForEmptyFolder()
    {
        $this->app->config->set('database.backup.path', __DIR__ . '/resources/EmptyFolder');

        $this->tester->execute(array());

        $this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*You haven't saved any dumps.(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
    }

    public function testDumpListForNonEmptyFolder()
    {
        $this->app->config->set('database.backup.path', __DIR__ . '/resources/NonEmptyFolder');

        $this->tester->execute(array());
        // Need to find the good regex
        //$this->assertRegExp("/^(\\033\[[0-9;]*m)*(\\n)*Please select one of the following dumps:(\\n)*(\\033\[0m)*(\\033\[[0-9;]*m)*(\\n)*hello.sql(\\n)*(\\033\[0m)*(\\033\[[0-9;]*m)*(\\n)*world.sql(\\n)*(\\033\[0m)*$/", $this->tester->getDisplay());
    }
}