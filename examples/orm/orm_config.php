<?php

include_once 'A/Db/Pdo.php';
include_once 'A/Locator.php';

$locator = new A_Locator();
$locator->register(array( 
		'A_Db_Pdo' => array( 
			'__construct' => array($config['db']), 
			), 
		)
	);
#$db = $locator->get('DB', 'A_Db_Pdo') or die ('Error: could not connect to DB');
#$db = new A_Db_Pdo($config['db']) or die ('Error: could not connect to DB');

