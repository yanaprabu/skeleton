<?php
ini_set('date.timezone', 'America/Los_Angeles');

include 'config.php';
// need to set the database type for PDO factory
$ConfigArray['DBDSN']['phptype'] = 'mysql';

//include 'A/Db/Pdo.php';
include 'A/Db/Tabledatagateway.php';

class Projects extends A_Db_Tabledatagateway
{
}

$db = new A_Db_Pdo($ConfigArray['DBDSN']);
$db->connect();
if ($db->isError()) die('ERROR: ' . $db->getMessage());

$project = new Projects($db, 'users');

$rows = $project->find(1);
dump($project->sql);
dump($rows);
// Get the current row
dump($rows->current());

// Get all rows
$all = $rows->fetchAll();
dump($all);

// Update some data
$data = array('lastname'=>'testert');
$updated = $project->update($data, 'id = 1');
//dump($updated);
$rows = $project->find(1);
dump($rows->current());
