<?php

use Schickling\Backup\DatabaseBuilder;

class DatabaseBuilderTest extends \PHPUnit_Framework_TestCase
{

    protected $databaseBuilder;

    public function testMySQL()
    {
        $config = array(
            'default'       => 'mysqlTest',
            'connections'   => array(
                'mysqlTest' => array(
                    'driver'    => 'mysql',
                    'host'      => 'localhost',
                    'database'  => 'database',
                    'username'  => 'root',
                    'password'  => '',
                    )
                )
            );

        $this->databaseBuilder = new DatabaseBuilder($config);

        $this->assertInstanceOf('Schickling\Backup\Databases\MySQLDatabase', $this->databaseBuilder->getDatabase());
    }

    public function testSqlite()
    {
        $config = array(
            'default'       => 'sqliteTest',
            'connections'   => array(
                'sqliteTest' => array(
                    'driver'   => 'sqlite',
                    'database' => __DIR__.'/../database/production.sqlite',
                    )
                )
            );

        $this->databaseBuilder = new DatabaseBuilder($config);

        $this->assertInstanceOf('Schickling\Backup\Databases\SqliteDatabase', $this->databaseBuilder->getDatabase());
    }

    public function testUnsupported()
    {
        $config = array(
            'default'       => 'unsupportedTest',
            'connections'   => array(
                'unsupportedTest' => array(
                    'driver'   => 'unsupported',
                    )
                )
            );

        $this->setExpectedException('Exception');

        $this->databaseBuilder = new DatabaseBuilder($config);
    }

}