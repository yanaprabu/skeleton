<?php
include 'config.php';

$db = new A_Db_Mysqli($ConfigArray['DBDSN']);
$db->connect();
if (! $db->isError()) {

	$sql = "SELECT * FROM users";
	$result = $db->query($sql);
	if (! $db->isError()) {
		dump($result->fetch_object(), '__call: fetch_object(): ');
		dump($result->lengths, '__get: LENGTHS: ');
		
		$row = $result->fetchAll();
dump($row, 'ROW: ');

	} else {
		echo 'connect ERROR: ' . $db->getErrorMsg();
	}
	

} else {
	echo 'connect ERROR: ' . $db->getErrorMsg();
}


