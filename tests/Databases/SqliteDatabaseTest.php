<?php

use Schickling\Backup\Databases\SqliteDatabase;
use Mockery as m;

class SqliteDatabaseTest extends \PHPUnit_Framework_TestCase
{

    protected $console;
    protected $database;

    public function setUp()
    {
        $this->console = m::mock('Schickling\Backup\Console');
        $this->database = new SqliteDatabase($this->console, 'testDatabase.sqlite');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testDump()
    {
        $this->console->shouldReceive('run')
                      ->with("cp 'testDatabase.sqlite' 'testfile.sqlite'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->dump('testfile.sqlite'));
    }

    public function testDumpFails()
    {
        $this->console->shouldReceive('run')
                      ->with("cp 'testDatabase.sqlite' 'testfile.sqlite'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->dump('testfile.sqlite'));
    }

    public function testRestore()
    {
        $this->console->shouldReceive('run')
                      ->with("cp -f 'testfile.sqlite' 'testDatabase.sqlite'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->restore('testfile.sqlite'));
    }

    public function testRestoreFails()
    {
        $this->console->shouldReceive('run')
                      ->with("cp -f 'testfile.sqlite' 'testDatabase.sqlite'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->restore('testfile.sqlite'));
    }

}