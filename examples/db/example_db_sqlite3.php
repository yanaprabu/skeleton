<?php
include 'config.php';

$filename = 'sqlite3.db';

$db = new A_Db_Sqlite3(array('filename'=>$filename));
$db->connect();
if (! $db->isError()) {
	$test_rows = array(
		0 => array(
			':id' => 10,
			':name' => 'Foo',
			),
		1 => array(
			':id' => 20,
			':name' => 'Bar',
			),
		);
	$sql = "CREATE TABLE test1 (id INT, name VARCHAR(100))";
	$db->query($sql);
	
	foreach ($test_rows as $row) {
		$result = $db->query("INSERT INTO test1 (id, name) VALUES (:id, ':name')", $row);
	}

	$sql = "SELECT * FROM test1";
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

$db->close();

unlink($filename);

dump();
