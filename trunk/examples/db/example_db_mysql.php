<?php
include 'config.php';
#include 'A/Db/Sqlite.php';
#include 'A/Db/Activerecord.php';

$db = new A_Db_MySQL($ConfigArray['DBDSN']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

dump($project->sql);
dump($project->toArray());
