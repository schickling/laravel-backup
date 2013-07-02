<?php

use Schickling\Backup\Console;

class ConsoleTest extends \PHPUnit_Framework_TestCase
{

    protected $console;

    public function setUp()
    {
        $this->console = new Console();
    }

    public function testSuccess()
    {
        $this->assertTrue($this->console->run('true'));
    }

    public function testFailure()
    {
        $this->assertFalse($this->console->run('false'));
    }

}