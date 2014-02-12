<?php

use Schickling\Backup\Databases\MySQLDatabase;
use Mockery as m;

class MySQLDatabaseTest extends \PHPUnit_Framework_TestCase
{

    protected $console;
    protected $database;

    public function setUp()
    {
        $this->console = m::mock('Schickling\Backup\Console');
        $this->database = new MySQLDatabase($this->console, 'testDatabase', 'testUser', 'password', 'localhost', '3306');
    }

    public function tearDown()
    {
        m::close();
    }

    public function testDump()
    {
        $this->console->shouldReceive('run')
                      ->with("mysqldump --user='testUser' --password='password' --host='localhost' --port='3306' 'testDatabase' > 'testfile.sql'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->dump('testfile.sql'));
    }

    public function testDumpFails()
    {
        $this->console->shouldReceive('run')
                      ->with("mysqldump --user='testUser' --password='password' --host='localhost' --port='3306' 'testDatabase' > 'testfile.sql'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->dump('testfile.sql'));
    }

    public function testRestore()
    {
        $this->console->shouldReceive('run')
                      ->with("mysql --user='testUser' --password='password' --host='localhost' --port='3306' 'testDatabase' < 'testfile.sql'")
                      ->once()
                      ->andReturn(true);

        $this->assertTrue($this->database->restore('testfile.sql'));
    }

    public function testRestoreFails()
    {
        $this->console->shouldReceive('run')
                      ->with("mysql --user='testUser' --password='password' --host='localhost' --port='3306' 'testDatabase' < 'testfile.sql'")
                      ->once()
                      ->andReturn(false);

        $this->assertFalse($this->database->restore('testfile.sql'));
    }

}
