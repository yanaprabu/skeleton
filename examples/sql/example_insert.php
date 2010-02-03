<?php
include 'config.php';

$values = array(
	array('foo'=>'Boo', 'bar'=>'Ca\'r', 'baz'=>'Caz'),
	array('goo'=>'Goo', 'gar'=>'Ga\'r', 'gaz'=>'Gaz'),
	);
$insert = new A_Sql_Insert();
$insert->table('mytable')->values($values);
echo "A_Sql_Insert::render=" . $insert->render() . '<br/>';

dump($insert);