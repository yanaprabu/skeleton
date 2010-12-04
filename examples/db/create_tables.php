<?php
include 'config.php';
include 'A/Db/Sqlite3.php';
include 'A/Db/Tabledatagateway.php';

class Projects extends A_Db_Tabledatagateway
{
}

$db = new A_Db_Sqlite3($ConfigArray['DBDSN']);
$db->connect();

$sql = "CREATE TABLE clients (
  id INTEGER PRIMARY KEY,
  name TEXT
);";
$db->query($sql);
$db->query("INSERT INTO clients VALUES (1, 'Project One')");
$db->query("INSERT INTO clients VALUES (2, 'Project Two')");
$db->query("INSERT INTO clients VALUES (3, 'Project Three')");

$sql = "CREATE TABLE projects (
  id INTEGER PRIMARY KEY,
  clients_id INTEGER,
  name TEXT
);";
$db->query($sql);
$db->query("INSERT INTO projects VALUES (1, 1, 'Project One')");
$db->query("INSERT INTO projects VALUES (2, 1, 'Project Two')");
$db->query("INSERT INTO projects VALUES (3, 2, 'Project Three')");
$db->query("INSERT INTO projects VALUES (4, 2, 'Project Four')");
$db->query("INSERT INTO projects VALUES (5, 2, 'Project Five')");
$db->query("INSERT INTO projects VALUES (6, 3, 'Project Six')");

$result = $db->query('SELECT * FROM clients');
while ($row = $result->fetchRow()) {
	var_dump($row); 
}
$result = $db->query('SELECT * FROM projects');
while ($row = $result->fetchRow()) {
	var_dump($row); 
}
