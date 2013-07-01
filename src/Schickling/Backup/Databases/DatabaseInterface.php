<?php namespace Schickling\Backup\Databases;

interface DatabaseInterface
{
	/**
	 * Create a database dump
	 * 
	 * @return boolean
	 */
	public function dump($destinationFile);

	/**
	 * Return the file extension of a dump file (sql, ...)
	 * 
	 * @return string
	 */
	public function getFileExtension();
}