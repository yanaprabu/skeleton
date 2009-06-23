<?php
include 'config.php';
include 'A/Sql/Insert.php';

#include 'A/Db/MySQL.php';
#$db = new A_Db_MySQL($configdata);

$values = array(
	array('foo'=>'Boo', 'bar'=>'Ca\'r', 'baz'=>'Caz'),
	array('goo'=>'Goo', 'gar'=>'Ga\'r', 'gaz'=>'Gaz'),
	);
$insert = new A_Sql_Insert();
$insert->table('mytable')->values($values);
echo "A_Sql_Insert::render=" . $insert->render() . '<br/>';

dump($insert);