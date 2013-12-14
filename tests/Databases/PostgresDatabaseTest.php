<?php

use Schickling\Backup\Databases\PostgresDatabase;
use Mockery as m;

class PostgresDatabaseTest extends \PHPUnit_Framework_TestCase
{

    protected $console;
    protected $database;

    public function setUp()
    {
        $this->console = m::mock('Schickling\Backup\Console');
        $this->database = new PostgresDatabase($this->console, 'testDatabase', 'testUser', 'password', 'localhost');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testDump()
    {
        $this->console->shouldReceive('run')
                      ->with("PGPASSWORD='password' pg_dump -Fc --no-acl --no-owner -h 'localhost' -U 'testUser' 'testDatabase' > 'testfile.dump'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->dump('testfile.dump'));
    }

    public function testDumpFails()
    {
        $this->console->shouldReceive('run')
                      ->with("PGPASSWORD='password' pg_dump -Fc --no-acl --no-owner -h 'localhost' -U 'testUser' 'testDatabase' > 'testfile.dump'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->dump('testfile.dump'));
    }

    public function testRestore()
    {
        $this->console->shouldReceive('run')
                      ->with("PGPASSWORD='password' pg_restore --verbose --clean --no-acl --no-owner -h 'localhost' -U 'testUser' -d 'testDatabase' 'testfile.dump'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->restore('testfile.dump'));
    }

    public function testRestoreFails()
    {
        $this->console->shouldReceive('run')
                      ->with("PGPASSWORD='password' pg_restore --verbose --clean --no-acl --no-owner -h 'localhost' -U 'testUser' -d 'testDatabase' 'testfile.dump'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->restore('testfile.dump'));
    }

}
