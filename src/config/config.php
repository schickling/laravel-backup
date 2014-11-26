<?php

return array(

	'path' => storage_path() . '/dumps/',

	'filename_format' => '\d\b\_YmdHis',

	'mysql' => array(
		'dump_command_path' => '',
		'restore_command_path' => '',
		'compress' => false,
		),

	's3' => array(
		'path' => ''
		),

	);
