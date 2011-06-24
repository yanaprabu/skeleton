<?php
include 'config.php';

$db = new A_Db_Mysql($ConfigArray['DBDSN']);
$db->connect();
if (! $db->isError()) {

	$sql = "SELECT * FROM users";
	$result = $db->query($sql);
	if (! $db->isError()) {
		$row = $result->fetchAll();
dump($row, 'ROW: ');

	} else {
		echo 'connect ERROR: ' . $db->getErrorMsg();
	}

} else {
	echo 'connect ERROR: ' . $db->getErrorMsg();
}

dump();
