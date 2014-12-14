<?php

use Schickling\Backup\DatabaseBuilder;

class DatabaseBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testMySQL()
    {
        $config = array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'database',
            'username'  => 'root',
            'password'  => '',
            'port'      => '3307',
        );

        $databaseBuilder = new DatabaseBuilder();
        $database = $databaseBuilder->getDatabase($config);

        $this->assertInstanceOf('Schickling\Backup\Databases\MySQLDatabase', $database);
    }

    public function testSqlite()
    {
        $config = array(
            'driver'   => 'sqlite',
            'database' => __DIR__.'/../database/production.sqlite',
        );

        $databaseBuilder = new DatabaseBuilder();
        $database = $databaseBuilder->getDatabase($config);

        $this->assertInstanceOf('Schickling\Backup\Databases\SqliteDatabase', $database);
    }

    public function testPostgres() {
        $config = array(
            'driver'    => 'pgsql',
            'host'      => 'localhost',
            'database'  => 'database',
            'username'  => 'root',
            'password'  => 'paso',
        );

        $databaseBuilder = new DatabaseBuilder();
        $database = $databaseBuilder->getDatabase($config);

        $this->assertInstanceOf('Schickling\Backup\Databases\PostgresDatabase', $database);
    }

    public function testUnsupported()
    {
        $config = array(
            'driver'   => 'unsupported',
        );

        $this->setExpectedException('Exception');

        $databaseBuilder = new DatabaseBuilder();
        $database = $databaseBuilder->getDatabase($config);
    }
}