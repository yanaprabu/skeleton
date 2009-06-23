<?php

#$db = new PDO ("mysql:host=" . $config['db']['hostname'] . ";" . "dbname=" . $config['db']['database'], $config['db']['username'], $config['db']['password']) or die ('Error: could not connect to DB');
include_once 'A/Db/Pdo.php';
$db = new A_Db_Pdo($config['db']);
if (! $db->connect()) {
	die ('Error: could not connect to DB');
}
