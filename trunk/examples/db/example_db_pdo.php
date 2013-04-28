<?php
include 'config.php';

// need to set the database type for PDO factory
$ConfigArray['DBDSN']['phptype'] = 'mysql';

$db = new A_Db_Pdo($ConfigArray['DBDSN']);
$db->connect();
if (! $db->isError()) {

	$sql = "SELECT * FROM users";
	$result = $db->query($sql);
	if (! $db->isError()) {
		dump($result->fetch(), '__call: fetch(): ');
		
		$row = $result->fetchAll();
dump($row, 'ROW: ');

	} else {
		echo 'connect ERROR: ' . $db->getErrorMsg();
	}
	

} else {
	echo 'connect ERROR: ' . $db->getErrorMsg();
}

