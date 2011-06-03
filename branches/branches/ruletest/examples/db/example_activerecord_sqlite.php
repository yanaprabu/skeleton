<?php
include 'config.php';
#include 'A/Db/Sqlite.php';
#include 'A/Db/Activerecord.php';

class Projects extends A_Db_Activerecord
{
}

$db = new A_Db_Sqlite3($ConfigArray['DBDSN_SQLITE']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

$project = new Projects($db);

$project->find(2);
dump($project->sql);
dump($project->toArray());
