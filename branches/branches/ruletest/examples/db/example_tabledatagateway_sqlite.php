<?php
include 'config.php';
#include 'A/Db/Sqlite.php';
#include 'A/Db/Tabledatagateway.php';

class Projects extends A_Db_Tabledatagateway
{
}

$db = new A_Db_Sqlite3($ConfigArray['DBDSN_SQLITE']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

$project = new Projects($db);

$rows = $project->find(2);
dump($project->sql);
dump($rows);

$rows = $project->find('clients_id=', 1);
dump($project->sql);
dump($rows);
