<?php

return array(

	'path' => storage_path() . '/dumps/',

	'mysql' => array(
		'dump_command_path' => '',
		'restore_command_path' => '',
		),

	's3' => array(
		'path' => ''
		),

	'postgres' => array(
		'exclude_table_data' => array(
			//'cache',
			//'failed_jobs',
			//'sessions',
			),
		),

);
